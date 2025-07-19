<?php
namespace App\Message;

class SendCommentMessage
{
    public function __construct(private readonly int $commentId)
    {
    }

    public function getCommentId(): int
    {
        return $this->commentId;
    }
}
