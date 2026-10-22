<?php
declare(strict_types=1);

namespace App;

use PHPMailer\PHPMailer\Exception as MailException;
use PHPMailer\PHPMailer\PHPMailer;

final class Mailer
{
    public static function send(string $to, string $subject, string $html, ?string $replyTo = null, ?string $replyName = null): bool
    {
        require_once VENDOR_PATH . '/PHPMailer/src/Exception.php';
        require_once VENDOR_PATH . '/PHPMailer/src/PHPMailer.php';
        require_once VENDOR_PATH . '/PHPMailer/src/SMTP.php';

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = (string) config('mail.host');
            $mail->SMTPAuth = config('mail.username') !== '';
            $mail->Username = (string) config('mail.username');
            $mail->Password = (string) config('mail.password');
            $enc = strtolower((string) config('mail.encryption'));
            if ($enc === 'ssl') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            } elseif ($enc === 'tls') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            }
            $mail->Port = (int) config('mail.port');
            $mail->CharSet = 'UTF-8';
            $mail->setFrom((string) config('mail.from_email'), (string) config('mail.from_name'));
            $mail->addAddress($to);
            if ($replyTo) {
                $mail->addReplyTo($replyTo, $replyName ?: $replyTo);
            }
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $html;
            $mail->AltBody = trim(html_entity_decode(strip_tags($html), ENT_QUOTES, 'UTF-8'));
            $mail->send();
            return true;
        } catch (MailException $e) {
            Logger::error('Mail send failed', ['to' => $to, 'error' => $mail->ErrorInfo]);
            return false;
        }
    }
}
