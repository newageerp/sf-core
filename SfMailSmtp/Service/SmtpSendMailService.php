<?php

namespace Newageerp\SfMailSmtp\Service;

use Newageerp\SfMail\Service\MailSendService;
use PHPMailer\PHPMailer\PHPMailer;

class SmtpSendMailService extends MailSendService
{
    public function sendMail(
        string $fromName,
        string $fromEmail,
        string $subject,
        string $content,
        array  $recipients,
        ?array $attachments = [],
        string $customId = '',
    )
    {
        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host = $_ENV['SF_MAIL_SMTP_HOST'];
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['SF_MAIL_SMTP_USER'];
        $mail->Password = $_ENV['SF_MAIL_SMTP_PSW'];
        $mail->SMTPSecure = $_ENV['SF_MAIL_SMTP_SECURE'];
        $mail->Port = (int)$_ENV['SF_MAIL_SMTP_PORT'];
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';

        foreach ($attachments as $file) {
            $mail->addStringAttachment(
                $file['Base64Content'],
                $file['Filename'],
                PHPMailer::ENCODING_BASE64,
                $file['ContentType']
            );
        }

        //Recipients
        $mail->setFrom(
            $_ENV['SF_MAIL_SMTP_FROM_EMAIL'],
            $_ENV['SF_MAIL_SMTP_FROM_NAME']
        );
        if ($fromEmail !== '') {
            $mail->addReplyTo($fromEmail, $fromName);
        }

        foreach ($recipients as $key => $recipient) {
            if ($key === 0) {
                $mail->addAddress($recipient, '');
            } else {
                $mail->addCC($recipient, '');
            }
        }

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $content;

        $mail->send();
    }
}