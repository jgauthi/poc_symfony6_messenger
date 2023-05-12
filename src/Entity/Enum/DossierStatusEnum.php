<?php
namespace App\Entity\Enum;

enum DossierStatusEnum: int
{
    case PREPARATION = 0;
    case ACTIVE = 1;
    case PAUSED = 2;
    case CLOSED = 3; // finished without resolution
    case FINISHED = 4;
}
