<?php
namespace App\Service;

use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class SendDossierCommentsService
{
    public function __construct(private readonly MailerInterface $mailer, private EntityManagerInterface $em)
    {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function send(Comment $comment): void
    {
        $email = (new TemplatedEmail)
            ->from(new Address('dossier+comments@sfdemo.fr', 'SF-Demo'))
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
