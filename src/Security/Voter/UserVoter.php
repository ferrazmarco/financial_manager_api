<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\User;

class UserVoter extends Voter
{
    public const VIEW = 'view';
    public const SHOW = 'show';
    public const EDIT = 'edit';
    public const DESTROY = 'destroy';

    protected function supports(string $attribute, mixed $subject): bool
    {   
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::VIEW, self::DESTROY, self::SHOW]);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {   
        $currentUser = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$currentUser instanceof User) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        return match($attribute) {
            self::VIEW => $this->canView($subject, $currentUser),
            self::EDIT => $this->canEdit($subject, $currentUser),
            self::DESTROY => $this->canDestroy($subject, $currentUser),
            self::SHOW => $this->canShow($subject, $currentUser)
        };
    }

    private function canView(?User $subject, User $currentUser): bool
    {   
        return $currentUser->isAdmin();
    }

    private function canShow(User $subject, User $currentUser): bool
    {   
        return false;
        return ($currentUser === $subject || $currentUser->isAdmin());
    }

    private function canEdit(User $subject, User $currentUser): bool
    {
        return ($currentUser === $subject || $currentUser->isAdmin());
    }

    private function canDestroy(User $subject, User $currentUser): bool
    {
        return ($currentUser !== $subject || $currentUser->isAdmin());
    }
}
