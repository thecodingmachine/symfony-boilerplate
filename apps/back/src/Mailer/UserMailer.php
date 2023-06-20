<?php

declare(strict_types=1);

namespace App\Mailer;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class UserMailer
{
    public function __construct(
        private readonly MailerInterface $mailer,
    ) {
    }

    public function sendRegistrationMail(User $user): void
    {
        $email = (new TemplatedEmail())
            ->from(getenv('MAIL_HOST'))
            ->to($user->getEmail())
            ->subject('Registration')
            ->htmlTemplate('emails/register.html.twig');

        $this->mailer->send($email);
    }
}