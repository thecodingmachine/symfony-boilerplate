<?php

declare(strict_types=1);

namespace App\UseCase;

use App\Domain\Model\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class CreateEmail
{
    public function __construct(
        private TranslatorInterface $translator,
        private string $mailTitle,
        private string $mailFromAddress,
        private string $mailFromName,
    ) {
    }

    /**
     * @param array<string,string> $context
     */
    protected function create(User $user, string $subjectId, string $template, array $context): TemplatedEmail
    {
        $context['title']  = $this->mailTitle;
        $context['domain'] = 'emails';
        $context['locale'] = $user->getLocale();

        $translatedSubject = $this->translator
            ->trans(
                $subjectId,
                [],
                $context['domain'],
                $context['locale']
            );

        $email = (new TemplatedEmail())
            ->to(new Address($user->getEmail(), $user->getFirstName() . ' ' . $user->getLastName()))
            ->from(new Address($this->mailFromAddress, $this->mailFromName))
            ->subject($translatedSubject)
            ->htmlTemplate($template)
            ->context($context);

        // This header tells auto-repliers ("email holiday mode") to not
        // reply to this message because it's an automated email.
        $email->getHeaders()->addTextHeader('X-Auto-Response-Suppress', 'OOF, DR, RN, NRN, AutoReply');

        return $email;
    }
}
