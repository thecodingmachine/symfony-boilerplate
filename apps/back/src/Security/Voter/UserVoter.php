<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\User;
use App\Security\Enum\Right;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

/** @phpstan-extends Voter<string, User|null> */
class UserVoter extends Voter
{
    public const CREATE_USER = 'CREATE_USER';
    public const EDIT_ANY_USER = 'EDIT_ANY_USER';
    public const VIEW_ANY_USER = 'VIEW_ANY_USER';
    public const DELETE_ANY_USER = 'DELETE_ANY_USER';

    public function __construct(private readonly Security $security)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return \in_array($attribute, [
            self::CREATE_USER,
            self::VIEW_ANY_USER,
            self::EDIT_ANY_USER,
            self::DELETE_ANY_USER,
        ]);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        return match ($attribute) {
            self::CREATE_USER => $this->canCreate(),
            self::EDIT_ANY_USER, self::DELETE_ANY_USER => $this->canEdit($subject),
            self::VIEW_ANY_USER => $this->canView(),
            default => false
        };
    }

    private function canCreate(): bool
    {
        return $this->security->isGranted(Right::ROLE_RIGHT_USER_CREATE->value);
    }

    private function canEdit(mixed $subject): bool
    {
        if (! $subject instanceof User) {
            throw new \RuntimeException('Subject of voter should be a ' . User::class);
        }

        return $this->security->isGranted(Right::ROLE_RIGHT_USER_UPDATE->value)
            && ! $subject->hasRole('ROLE_ADMIN');
    }

    private function canView(): bool
    {
        return $this->security->isGranted(Right::ROLE_RIGHT_USER_READ->value);
    }
}
