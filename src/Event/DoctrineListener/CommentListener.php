<?php
namespace App\Event\DoctrineListener;

use App\Entity\Comment;
use App\Message\SendComment;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs as BaseLifecycleEventArgs;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsDoctrineListener(event: Events::preUpdate)]
class CommentListener
{
    public function __construct(private MessageBusInterface $bus)
    {
    }

    public function postPersist(Comment $comment, BaseLifecycleEventArgs $event): void
    {
        if (!empty($comment->getId()) && !$comment->getSent()) {
            $this->bus->dispatch(new SendComment($comment->getId()));
        }
    }
}
