<?php

declare(strict_types=1);

namespace App\UseCase;

use App\Domain\Model\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use Symfony\Contracts\Translation\TranslatorInterface;

use function strval;

abstract class CreateEmail
{
    private TranslatorInterface $translator;
    private string $mailTitle;
    private string $mailFromAddress;
    private string $mailFromName;

    public function __construct(
        TranslatorInterface $translator,
        string $mailTitle,
        string $mailFromAddress,
        string $mailFromName
    ) {
        $this->translator      = $translator;
        $this->mailTitle       = $mailTitle;
        $this->mailFromAddress = $mailFromAddress;
        $this->mailFromName    = $mailFromName;
    }

    /**
     * @param array<string,string> $context
     */
    protected function create(User $user, string $subjectId, string $template, array $context): TemplatedEmail
    {
        $context['title']  = $this->mailTitle;
        $context['domain'] = 'emails';
        $context['locale'] = strval($user->getLocale());

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
