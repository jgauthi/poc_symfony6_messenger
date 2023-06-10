<?php
namespace App\MessageHandler;

use App\Entity\Comment;
use App\Entity\User;
use App\Message\SendComment;
use App\Service\SendDossierCommentsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class SendCommentHandler
{
    public function __construct(private EntityManagerInterface $em, private SendDossierCommentsService $service)
    {
    }

    public function __invoke(SendComment $message): void
    {
        // do something with your message
        $user = $this->em->find(User::class, $message->getUserId());
        $comment = $this->em->find(Comment::class, $message->getCommentId());

        // On vérifie qu'on a toutes les informations nécessaires
        if($newsletter !== null && $user !== null){
            $this->service->send($comment);
        }
    }
}