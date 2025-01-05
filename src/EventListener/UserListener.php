<?php

namespace App\EventListener;

use App\Entity\User;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Event\PrePersistEventArgs;

class UserListener
{
    public function prePersist(User $user, PrePersistEventArgs $event): void
    {
        $user->setUpdatedAt(new \DateTimeImmutable());
    }

    public function preUpdate(User $user, PreUpdateEventArgs $event): void
    {
        if ($event->hasChangedField('last_signin')) {
            return;
        }
        $user->setUpdatedAt(new \DateTimeImmutable());
    }
}
