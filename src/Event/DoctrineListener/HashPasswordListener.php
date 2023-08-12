<?php
namespace App\Event\DoctrineListener;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsDoctrineListener(event: Events::prePersist)]
#[AsDoctrineListener(event: Events::preUpdate)]
class HashPasswordListener
{
    public function __construct(private UserPasswordHasherInterface $hasher)
    {
    }

    private function encodePassword(User $user): void
    {
        $password = $user->getPlainPassword();
        if (empty($password)) {
            return;
        }

        $hashed = $this->hasher->hashPassword($user, $password);
        $user->setPassword($hashed);
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        /** @var User $user */
        $user = $args->getObject();
        if (!$user instanceof User) {
            return;
        }

        $this->encodePassword($user);
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        /** @var User $user */
        $user = $args->getObject();
        if (!$user instanceof User) {
            return;
        }

        $this->encodePassword($user);
    }
}
