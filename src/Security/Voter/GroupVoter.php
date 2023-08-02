<?php

namespace App\Security\Voter;

use App\Entity\User;
use App\Entity\Group;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class GroupVoter extends Voter
{
    protected function supports(string $attribute, mixed $subject): bool
    {   
        return $subject instanceof Group;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        if ($subject->getUsers()->contains($user) && $subject->getCreatedBy() === $user) {
            return true;
        }

        return $subject->getUsers()->contains($user);
    }
}
