<?php

namespace App;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class Mailer
{
    /**
     * @var string $subject
     */
    private $subject;

    /**
     * @var string $subject
     */
    private $body;

    /**
     * @var string $subject
     */
    private $emailTo;

    /**
     * @var string $subject
     */
    private $nameTo;

    /**
     * @var array $attachment
     */
    private $attachment;

    /**
     * @param $subject
     * @param $body
     * @param $emailTo
     * @param $nameTo
     * @param $attachments
     */
    public function __construct($subject, $body, $emailTo, $nameTo, $attachments = null)
    {
        $this->subject = $subject;
        $this->body = $body;
        $this->emailTo = $emailTo;
        $this->nameTo = $nameTo;
        $this->attachments = $attachments;
    }

    /**
     * @throws Exception
     */
    public function send()
    {
        $mail = new PHPMailer();

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.mail.ru';
            $mail->SMTPAuth = true;
            $mail->Username = 'kdf_16@mail.ru';
            $mail->Password = 'gerdkopgerdkop016#';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            $mail->CharSet  = 'UTF-8';
            $mail->setLanguage('ru');

            //Recipients
            $mail->setFrom('kdf_16@mail.ru', 'Казанский Дом Фасадов');
            $mail->addAddress($this->emailTo, $this->nameTo);

            // Content
            $mail->isHTML(true);
            $mail->Subject =  $this->subject;
            $mail->Body = $this->body;
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            if(!empty($this->attachments)){
                foreach ($this->attachments as $attachment) {
                    $mail->addAttachment($attachment['path'], $attachment['name']);
                }
            }

            $mail->send();

        } catch (Exception $e) {
            throw new Exception ("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        }
    }
}

