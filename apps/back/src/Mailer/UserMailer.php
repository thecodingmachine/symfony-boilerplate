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
        private string $mailHost,
    ) {
    }

    public function sendRegistrationMail(User $user): void
    {
        $email = (new TemplatedEmail())
            ->from($this->mailHost)
            ->to($user->getEmail())
            ->subject('Registration')
            ->htmlTemplate('emails/register.html.twig');

        $this->mailer->send($email);
    }
}
