<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class MailerService
{

    public function __construct(private MailerInterface $mailer){}

    public function sendEmail(string $expediteur, string $sujet, string $message): void
    {
        $email = (new TemplatedEmail())
            ->from('contact@mmiple.fr')
            ->replyTo($expediteur)
            ->to('administrateur@mmiple.fr')
            ->subject($sujet)
            ->text($message);

        $this->mailer->send($email);
    }

    public function sendTemplateEmail(string $expediteur, string $sujet, string $message): void
    {
        $email = (new TemplatedEmail())
            ->from('contact@mmiple.fr')
            ->replyTo($expediteur)
            ->to('administrateur@mmiple.fr')
            ->subject($sujet)
            ->html('mails/mail.html.twig')
            ->context([
                'message' => $message,
                'expediteur' => $expediteur,
            ]);

        $this->mailer->send($email);
    }
}
