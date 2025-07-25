<?php
namespace App\Message\Handler;

use App\Message\SendCommentMessage;
use App\Repository\CommentRepository;
use App\Service\SendDossierCommentsService;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class SendCommentMessageHandler
{
    public function __construct(private CommentRepository $commentRepository, private SendDossierCommentsService $service)
    {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function __invoke(SendCommentMessage $message): void
    {
        $comment = $this->commentRepository->find($message->getCommentId());

        if (!empty($comment) && empty($comment->getSent())) {
            $this->service->send($comment);
        }
    }
}
