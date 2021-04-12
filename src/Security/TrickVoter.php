<?php

namespace App\Security;

use App\Entity\Trick;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class TrickVoter extends Voter
{
    public const TRICK_DELETE = 'trick_delete';

    /**
     * {@inheritDoc}
     */
    protected function supports(string $attribute, $subject): bool
    {
        if (self::TRICK_DELETE !== $attribute) {
            return false;
        }

        if (!$subject instanceof Trick) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritDoc}
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        /** @var Trick $trick */
        $trick = $subject;

        return $trick->getAuthor() === $user || \in_array('ROLE_ADMIN', $user->getRoles(), true);
    }
}
