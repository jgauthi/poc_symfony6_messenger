<?php
namespace App\Service;

use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class SendDossierCommentsService
{
    private Address $addressFrom;

    public function __construct(
        private readonly MailerInterface $mailer,
        readonly string $appTitle,
        #[Autowire(env: 'MAILER_FROM')] readonly string $mailerFrom,
        private EntityManagerInterface $em,
    ) {
        $this->addressFrom = new Address($mailerFrom, $appTitle);
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function send(Comment $comment): void
    {
        $email = (new TemplatedEmail)
            ->from($this->addressFrom)
            ->to(new Address($comment->getDossier()->getAuthor()->getEmail(), $comment->getDossier()->getAuthor()->getName()))
            ->subject('New comment in folder: '.$comment->getDossier()->getTitle())
            ->htmlTemplate('email/new_comment.html.twig')
            ->context(compact('comment'))
        ;

        if (!empty($comment->getAuthor())) {
            $email->addCc(new Address($comment->getAuthor()->getEmail(), $comment->getAuthor()->getName()));
        }

        $this->mailer->send($email);

        $comment->setSent(new \DateTime);
        $this->em->persist($comment);
        $this->em->flush();
    }
}
