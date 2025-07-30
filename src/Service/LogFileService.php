<?php
namespace App\Service;

use Symfony\Component\HttpKernel\KernelInterface;

class LogFileService
{
    private const string DEFAULT_FILE = 'messenger';
    private string $logDir;

    public function __construct(KernelInterface $kernel)
    {
        $this->logDir = $kernel->getLogDir();
    }

    public function log(string $message, string $name = self::DEFAULT_FILE): void
    {
        $now = new \DateTime;
        $logFile = sprintf('%s/%s-%s.log', $this->logDir, $name, $now->format('Y-m-d'));

        error_log(
            sprintf('[%s] %s'.PHP_EOL, $now->format('H:i:s'), $message),
            3,
            $logFile,
        );
    }
}
