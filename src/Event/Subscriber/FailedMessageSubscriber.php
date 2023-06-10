<?php
namespace App\Event\Subscriber;

use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Event\WorkerMessageFailedEvent;
use Symfony\Component\Mime\Email;

class FailedMessageSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly MailerInterface $mailer)
    {
    }

    public function getSubscribedEvents(): array
    {
        return [
            WorkerMessageFailedEvent::class => 'onMessageFailed',
        ];
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function onMessageFailed(WorkerMessageFailedEvent $event): void
    {
        $error = get_class($event->getEnvelope()->getMessage());
        $trace = $event->getThrowable()->getTraceAsString();
        $email = (new Email)
            ->from('mailer+noreply@sfdemo.fr')
            ->to('admin@sfdemo.fr')
            ->subject('Echec d\'envoi')
            ->text('Une erreur est survenue: '.$error.PHP_EOL.$trace)
        ;
        $this->mailer->send($email);
    }
}
