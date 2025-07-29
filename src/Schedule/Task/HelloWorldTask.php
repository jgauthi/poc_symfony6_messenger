<?php
namespace App\Schedule\Task;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Scheduler\Attribute\AsCronTask;

#[AsCronTask('* 8-20 * * *')]
class HelloWorldTask
{
    private string $logDir;

    public function __construct(KernelInterface $kernel)
    {
        $this->logDir = $kernel->getLogDir();
    }

    public function __invoke(): void
    {
        $date = (new \DateTime)->format('Y-m-d H:i:s');
        $logFile = sprintf('%s/messenger-%s.log', $this->logDir, date('Y-m-d'));
        error_log("[{$date}] Hello World".PHP_EOL, 3, $logFile);
    }
}
