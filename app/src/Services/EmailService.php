<?php

namespace App\Services;


use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class EmailService
{
    private MailerInterface $mailer;
    public function __construct(MailerInterface $mailer)
        {
        $this->mailer = $mailer;

        }
    public function send( $form, string $to, string $subject, string $template, array $context =[]): void
    {
        $email = (new TemplatedEmail())
            ->from($form)
            ->to($to)
            ->subject($subject)
            ->htmlTemplate("emails/$template.html.twig")
            ->context($context);
        $this->mailer->send($email);
    }
}
