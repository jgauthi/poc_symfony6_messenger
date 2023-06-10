<?php
namespace App\Message;

class SendComment
{
    public function __construct(private readonly int $userId, private readonly int $dossierId)
    {
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getDossierId(): int
    {
        return $this->dossierId;
    }
}
