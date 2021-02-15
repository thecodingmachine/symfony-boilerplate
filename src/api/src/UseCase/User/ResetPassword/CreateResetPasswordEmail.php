<?php

declare(strict_types=1);

namespace App\UseCase\User\ResetPassword;

use App\Domain\Model\ResetPasswordToken;
use App\Domain\Model\User;
use App\UseCase\CreateEmail;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Contracts\Translation\TranslatorInterface;

use function Safe\sprintf;

final class CreateResetPasswordEmail extends CreateEmail
{
    private string $mailWebappURL;
    private string $mailWebappUpdatePasswordRouteFormat;

    public function __construct(
        TranslatorInterface $translator,
        string $mailTitle,
        string $mailFromAddress,
        string $mailFromName,
        string $mailWebappURL,
        string $mailWebappUpdatePasswordRouteFormat
    ) {
        parent::__construct($translator, $mailTitle, $mailFromAddress, $mailFromName);
        $this->mailWebappURL                       = $mailWebappURL;
        $this->mailWebappUpdatePasswordRouteFormat = $mailWebappUpdatePasswordRouteFormat;
    }

    public function createEmail(
        User $user,
        ResetPasswordToken $resetPasswordToken,
        string $plainToken
    ): TemplatedEmail {
        return $this->create(
            $user,
            $user->isActivated() ? 'reset_password.subject' : 'welcome_new_user.subject',
            $user->isActivated() ? 'emails/reset_password.html.twig' : 'emails/welcome_new_user.html.twig',
            [
                'first_name' => $user->getFirstName(),
                'last_name' => $user->getLastName(),
                'update_password_url' =>
                    $this->mailWebappURL .
                    sprintf(
                        $this->mailWebappUpdatePasswordRouteFormat,
                        $user->getLocale(),
                        $resetPasswordToken->getId(),
                        $plainToken
                    ),
            ]
        );
    }
}
