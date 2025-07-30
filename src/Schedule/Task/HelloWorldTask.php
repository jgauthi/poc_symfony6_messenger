<?php
namespace App\Schedule\Task;

use App\Service\LogFileService;
use Symfony\Component\Scheduler\Attribute\AsCronTask;

#[AsCronTask('* 8-20 * * *')]
class HelloWorldTask
{
    public function __construct(private LogFileService $logFileService)
    { }

    public function __invoke(): void
    {
        $this->logFileService->log('Hello World');
    }
}
