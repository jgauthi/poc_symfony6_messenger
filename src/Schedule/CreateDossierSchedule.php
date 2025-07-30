<?php
namespace App\Schedule;

use App\Message\CreateScheduleDossierMessage;
use Symfony\Component\Scheduler\Attribute\AsSchedule;
use Symfony\Component\Scheduler\{RecurringMessage, Schedule, ScheduleProviderInterface};
use Symfony\Contracts\Cache\CacheInterface;

#[AsSchedule]
class CreateDossierSchedule implements ScheduleProviderInterface
{
    public function __construct(
        private CacheInterface $cache,
    ) {
    }

    public function getSchedule(): Schedule
    {
        return (new Schedule)
            ->stateful($this->cache) // ensure missed tasks are executed
        //  ->processOnlyLastMissedRun(true) // [SF7] ensure only last missed task is run

            // One by month at 2:00 AM on the first day of the month
            ->add(RecurringMessage::cron('0 2 1 * *', new CreateScheduleDossierMessage('maintenance')))
        ;
    }
}
