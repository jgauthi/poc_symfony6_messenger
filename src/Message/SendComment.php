<?php
namespace App\Message;

class SendComment
{
    public function __construct(private readonly int $commentId)
    {
    }

    public function getCommentId(): int
    {
        return $this->commentId;
    }
}
