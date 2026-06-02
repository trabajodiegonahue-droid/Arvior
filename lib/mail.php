<?php

/**
 * Capa de envío de correo con dos backends:
 *  - 'mail'   : PHP mail() nativo (Hostinger/cPanel típico, sin auth).
 *  - 'resend' : Resend.com API HTTPS (recomendado para deliverability).
 *
 * Se elige por la setting `mail_provider`. Las plantillas de leads viven en
 * settings y se renderizan con renderLeadTemplate().
 */

function renderTemplate(string $tpl, array $vars): string {
    return preg_replace_callback('/\{\{\s*([a-z0-9_]+)\s*\}\}/i', function ($m) use ($vars) {
        return (string) ($vars[$m[1]] ?? '');
    }, $tpl) ?? $tpl;
}

function leadTemplateVars(array $lead): array {
    $host  = $_SERVER['HTTP_HOST'] ?? '';
    $proto = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $id    = (int) ($lead['id'] ?? 0);
    $vars = [
        'name'       => (string) ($lead['name']    ?? ''),
        'email'      => (string) ($lead['email']   ?? ''),
        'phone'      => (string) ($lead['phone']   ?? '') ?: '—',
        'message'    => (string) ($lead['message'] ?? '') ?: '—',
        'source'     => (string) ($lead['source']  ?? '') ?: 'website',
        'created_at' => (string) ($lead['created_at'] ?? date('Y-m-d H:i')),
        'admin_url'  => $host ? ($proto . '://' . $host . '/admin/?id=' . $id) : '',
        'site_name'  => (string) getSetting('site_name', 'Mi Sitio'),
    ];
    // Exponer información del negocio para usar en plantillas: {{business_phone}},
    // {{business_email}}, {{business_whatsapp}}, {{social_facebook}}, etc.
    if (function_exists('businessInfo')) {
        foreach (businessInfo() as $k => $v) $vars[$k] = (string) $v;
        $waText = (string) getSetting('business_whatsapp_text', '');
        $vars['business_whatsapp_link'] = whatsappLink((string) getSetting('business_whatsapp', ''), $waText);
    }
    return $vars;
}

function renderLeadTemplate(string $tpl, array $lead): string {
    return renderTemplate($tpl, leadTemplateVars($lead));
}

/** @return array{email:string, name:string} */
function resolveFromAddress(?string $override = null): array {
    $provider = (string) getSetting('mail_provider', 'mail');
    $name     = (string) getSetting('site_name', 'Mi Sitio');

    if ($override && filter_var($override, FILTER_VALIDATE_EMAIL)) {
        return ['email' => $override, 'name' => $name];
    }
    if ($provider === 'resend') {
        $email = trim((string) getSetting('resend_from_email', ''));
        $rname = trim((string) getSetting('resend_from_name', ''));
        if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['email' => $email, 'name' => $rname !== '' ? $rname : $name];
        }
    }
    $legacy = trim((string) getSetting('notification_from', ''));
    if ($legacy && filter_var($legacy, FILTER_VALIDATE_EMAIL)) {
        return ['email' => $legacy, 'name' => $name];
    }
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    return ['email' => 'no-reply@' . preg_replace('/^www\./', '', $host), 'name' => $name];
}

/** @return array{ok:bool, error?:string} */
function sendMailDetailed(string $to, string $subject, string $body, ?string $html = null, ?string $fromOverride = null): array {
    $to = trim($to);
    if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
        return ['ok' => false, 'error' => 'Destinatario inválido.'];
    }
    $provider = (string) getSetting('mail_provider', 'mail');
    $from     = resolveFromAddress($fromOverride);
    $replyTo  = trim((string) getSetting('resend_reply_to', '')) ?: null;

    if ($provider === 'resend') {
        return sendViaResend($to, $subject, $body, $html, $from, $replyTo);
    }
    return sendViaPhpMail($to, $subject, $body, $html, $from, $replyTo);
}

function sendMail(string $to, string $subject, string $body, ?string $fromOverride = null): bool {
    return (bool) sendMailDetailed($to, $subject, $body, null, $fromOverride)['ok'];
}

function sendViaPhpMail(string $to, string $subject, string $text, ?string $html, array $from, ?string $replyTo): array {
    $fromHeader = mb_encode_mimeheader($from['name']) . ' <' . $from['email'] . '>';
    $headers = [
        'From: ' . $fromHeader,
        'Reply-To: ' . ($replyTo ?: $from['email']),
        'MIME-Version: 1.0',
        'X-Mailer: PHP/' . phpversion(),
    ];
    $subjectEnc = '=?UTF-8?B?' . base64_encode($subject) . '?=';

    if ($html === null) {
        $headers[] = 'Content-Type: text/plain; charset=UTF-8';
        $payload   = $text;
    } else {
        $boundary  = 'b_' . bin2hex(random_bytes(8));
        $headers[] = 'Content-Type: multipart/alternative; boundary="' . $boundary . '"';
        $payload  = "--$boundary\r\nContent-Type: text/plain; charset=UTF-8\r\nContent-Transfer-Encoding: 8bit\r\n\r\n$text\r\n";
        $payload .= "--$boundary\r\nContent-Type: text/html; charset=UTF-8\r\nContent-Transfer-Encoding: 8bit\r\n\r\n$html\r\n";
        $payload .= "--$boundary--\r\n";
    }
    $ok = @mail($to, $subjectEnc, $payload, implode("\r\n", $headers), '-f' . $from['email']);
    return $ok ? ['ok' => true] : ['ok' => false, 'error' => 'mail() devolvió false (revisar logs del servidor).'];
}

function sendViaResend(string $to, string $subject, string $text, ?string $html, array $from, ?string $replyTo): array {
    $apiKey = trim((string) getSetting('resend_api_key', ''));
    if ($apiKey === '') {
        return ['ok' => false, 'error' => 'Falta configurar la API key de Resend.'];
    }
    if (!function_exists('curl_init')) {
        return ['ok' => false, 'error' => 'cURL no está disponible en este servidor.'];
    }

    $payload = [
        'from'    => sprintf('%s <%s>', $from['name'], $from['email']),
        'to'      => [$to],
        'subject' => $subject,
        'text'    => $text,
    ];
    if ($html !== null) $payload['html'] = $html;
    if ($replyTo) $payload['reply_to'] = $replyTo;

    $ch = curl_init('https://api.resend.com/emails');
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 15,
        CURLOPT_HTTPHEADER     => [
            'Authorization: Bearer ' . $apiKey,
            'Content-Type: application/json',
        ],
        CURLOPT_POSTFIELDS     => json_encode($payload),
    ]);
    $body = curl_exec($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err  = curl_error($ch);
    curl_close($ch);

    if ($body === false) return ['ok' => false, 'error' => 'Error cURL: ' . $err];
    if ($code >= 200 && $code < 300) return ['ok' => true];
    $msg = $body;
    $j   = json_decode($body, true);
    if (is_array($j) && isset($j['message'])) $msg = $j['message'];
    return ['ok' => false, 'error' => "Resend $code: $msg"];
}

/* ===================== Notificaciones de leads ===================== */

function notifyLeadCreated(array $lead): void {
    $to = trim((string) getSetting('notification_email', ''));
    if (!$to) return;

    $subjectTpl = (string) getSetting('notification_subject', '[{{site_name}}] Nuevo lead: {{name}}');
    $bodyTpl    = (string) getSetting('notification_body', '');
    if ($bodyTpl === '') {
        $bodyTpl = "Nuevo lead recibido desde {{site_name}}\n\nNombre:   {{name}}\nEmail:    {{email}}\nTeléfono: {{phone}}\nMensaje:\n{{message}}\n\nFuente:   {{source}}\nFecha:    {{created_at}}\n\nVer en admin: {{admin_url}}";
    }
    sendMailDetailed($to, renderLeadTemplate($subjectTpl, $lead), renderLeadTemplate($bodyTpl, $lead));
}

function sendLeadAutoReply(array $lead): void {
    if (getSetting('autoreply_enabled', '0') !== '1') return;
    $subject = renderLeadTemplate((string) getSetting('autoreply_subject', 'Recibimos tu mensaje'), $lead);
    $text    = renderLeadTemplate((string) getSetting('autoreply_body', ''), $lead);
    $htmlTpl = (string) getSetting('autoreply_body_html', '');
    $html    = $htmlTpl !== '' ? renderLeadTemplate($htmlTpl, $lead) : null;
    sendMailDetailed($lead['email'], $subject, $text, $html);
}

/** @return array{ok:bool, error?:string} */
function sendTestNotificationEmail(string $to): array {
    $demo = [
        'id'         => 0,
        'name'       => 'Lead de prueba',
        'email'      => 'lead@example.com',
        'phone'      => '+54 11 5555 5555',
        'message'    => 'Este es un mensaje de prueba enviado desde el panel.',
        'source'     => 'test',
        'created_at' => date('Y-m-d H:i'),
    ];
    $subjectTpl = (string) getSetting('notification_subject', '[{{site_name}}] Nuevo lead: {{name}}');
    $bodyTpl    = (string) getSetting('notification_body', '');
    return sendMailDetailed(
        $to,
        '[TEST] ' . renderLeadTemplate($subjectTpl, $demo),
        renderLeadTemplate($bodyTpl, $demo)
    );
}
