<?php
namespace App\Service;

use App\Entity\Comment;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class SendDossierCommentsService
{
    public function __construct(private readonly MailerInterface $mailer)
    {
    }

    public function send(Comment $comment): void
    {
        $email = (new TemplatedEmail)
            ->from('dossier+comment@sfdemo.fr')
            ->to($comment->getDossier()->getAuthor()->getEmail())
            ->subject('New comment in folder: '.$comment->getDossier()->getTitle())
            ->htmlTemplate('emails/newsletter.html.twig')
            ->context(compact('comment'))
        ;
        $this->mailer->send($email);
    }
}