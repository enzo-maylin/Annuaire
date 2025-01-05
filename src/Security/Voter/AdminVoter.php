<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class AdminVoter extends Voter
{
//On fait la liste des permissions gérées par le Voter.
    public const ACCESS = 'PERM_VIEW';
    public const DELETED = 'PERM_DELETED';

    public function __construct( private Security $security)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::ACCESS, self::DELETED])
            && $subject instanceof User;
    }
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        switch ($attribute) {
            case self::ACCESS:
                if($this->security->isGranted('ROLE_ADMIN') && $user != null) {
                    return true;
                }
                return false;
            case self::DELETED:
                if(!$this->security->isGranted('ROLE_ADMIN') || in_array('ROLE_ADMIN', $subject->getRoles())){
                    return false;
                }
                return true;
        }
        return false;
    }
}