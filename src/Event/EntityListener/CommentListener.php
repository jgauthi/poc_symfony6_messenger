<?php
namespace App\Event\EntityListener;

use App\Entity\Comment;
use App\Message\SendCommentMessage;
use Doctrine\Persistence\Event\LifecycleEventArgs as BaseLifecycleEventArgs;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\Messenger\MessageBusInterface;

#[AutoconfigureTag(name: 'doctrine.orm.entity_listener')]
class CommentListener
{
    public function __construct(private MessageBusInterface $bus)
    {
    }

    public function postPersist(Comment $comment, BaseLifecycleEventArgs $event): void
    {
        if (!empty($comment->getId()) && !$comment->getSent()) {
            $this->bus->dispatch(new SendCommentMessage($comment->getId()));
        }
    }
}
