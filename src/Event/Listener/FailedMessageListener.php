<?php
namespace App\Event\Listener;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Event\WorkerMessageFailedEvent;
use Symfony\Component\Mime\Email;

#[AsEventListener(event: WorkerMessageFailedEvent::class, method: 'onMessageFailed')]
class FailedMessageListener
{
    public function __construct(
        private readonly MailerInterface $mailer,
        #[Autowire(env: 'MAILER_FROM')] private readonly string $mailerFrom,
        #[Autowire(env: 'MAILER_ADMIN')] private readonly string $mailerAdmin,
    ) {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function onMessageFailed(WorkerMessageFailedEvent $event): void
    {
        $error = get_class($event->getEnvelope()->getMessage());
        $trace = $event->getThrowable()->getTraceAsString();
        $email = (new Email)
            ->from($this->mailerFrom)
            ->to($this->mailerAdmin)
            ->subject('Echec d\'envoi')
            ->text('Une erreur est survenue: '.$error.PHP_EOL.$trace)
        ;
        $this->mailer->send($email);
    }
}
