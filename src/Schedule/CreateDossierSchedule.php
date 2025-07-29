<?php
namespace App\Schedule;

use App\Message\CreateScheduleDossierMessage;
use Symfony\Component\Scheduler\Attribute\AsSchedule;
use Symfony\Component\Scheduler\{RecurringMessage, Schedule, ScheduleProviderInterface};

#[AsSchedule]
class CreateDossierSchedule implements ScheduleProviderInterface
{
    public function getSchedule(): Schedule
    {
        return (new Schedule)
            // One by month at 2:00 AM on the first day of the month
            ->add(RecurringMessage::cron('0 2 1 * *', new CreateScheduleDossierMessage('maintenance')))
        ;
    }
}
