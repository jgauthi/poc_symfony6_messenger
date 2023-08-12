<?php
namespace App\Entity;

use App\Entity\Trait\CreatedDateTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: \App\Repository\CommentRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\EntityListeners(['App\Event\EntityListener\CommentListener'])]
class Comment
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    #[Groups('Comment')]
    private ?int $id = null;

    #[ORM\ManyToOne, ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    #[Groups('Comment')]
    private ?User $author = null;

    #[ORM\ManyToOne(inversedBy: 'comments'), ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Dossier $dossier;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\Length(min: 10), Assert\NotBlank]
    #[Groups('CommentDetails')]
    private string $content;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups('CommentDetails')]
    private ?\DateTimeInterface $sent = null;

    use CreatedDateTrait;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return $this->getContent();
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getDossier(): Dossier
    {
        return $this->dossier;
    }

    public function setDossier(Dossier $dossier): self
    {
        $this->dossier = $dossier;

        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getSent(): ?\DateTimeInterface
    {
        return $this->sent;
    }

    public function setSent(?\DateTimeInterface $sent): self
    {
        $this->sent = $sent;

        return $this;
    }
}
