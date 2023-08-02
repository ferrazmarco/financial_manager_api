<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use App\Entity\Expense;
use App\Entity\User;

class ExpenseVoter extends Voter
{
    public const DESTROY = 'destroy';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::DESTROY])
            && $subject instanceof Expense;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof User) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        return match ($attribute) {
            self::DESTROY => $this->canDestroy($subject, $user),
            default => false
        };
    }

    private function canDestroy(Expense $subject, User $user): bool
    {
        return $subject->getCreatedBy() === $user;
    }
}
