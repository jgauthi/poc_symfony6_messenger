<?php
namespace App\MessageHandler;

use App\Message\SendComment;
use App\Repository\CommentRepository;
use App\Service\SendDossierCommentsService;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class SendCommentHandler
{
    public function __construct(private CommentRepository $commentRepository, private SendDossierCommentsService $service)
    {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function __invoke(SendComment $message): void
    {
        $comment = $this->commentRepository->find($message->getCommentId());

        if (!empty($comment) && empty($comment->getSent())) {
            $this->service->send($comment);
        }
    }
}
