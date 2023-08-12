<?php
namespace App\Entity;

use App\Entity\Trait\LastUpdateTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\PasswordStrength;

#[ORM\Entity(repositoryClass: \App\Repository\UserRepository::class),
    UniqueEntity('username', errorPath: 'username'),
    UniqueEntity('email'),
    ORM\HasLifecycleCallbacks,
]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    public const ROLE_COMMENTATOR = 'ROLE_COMMENTATOR';
    public const ROLE_WRITER = 'ROLE_WRITER';
    public const ROLE_EDITOR = 'ROLE_EDITOR';
    public const ROLE_ADMIN = 'ROLE_ADMIN';

    public const DEFAULT_ROLES = [self::ROLE_COMMENTATOR];

    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    #[Groups('User')]
    private ?int $id;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 6, max: 255), Assert\NotBlank]
    #[Groups('User')]
    private string $username;

    #[ORM\Column(length: 255)]
    #[Assert\PasswordStrength(['minScore' => PasswordStrength::STRENGTH_STRONG])]
    private string $password;

    // Plain password. Used for model validation. Must not be persisted.
    #[Assert\PasswordStrength(['minScore' => PasswordStrength::STRENGTH_STRONG])]
    private ?string $plainPassword = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 6, max: 255), Assert\NotBlank]
    #[Groups('UserDetails')]
    private string $name;

    #[ORM\Column(length: 255)]
    #[Assert\Email, Assert\Length(min: 6, max: 255), Assert\NotBlank]
    #[Groups('UserDetails')]
    private string $email;

    #[ORM\Column(type: Types::SIMPLE_ARRAY, options: ['default' => self::ROLE_COMMENTATOR])]
    #[Groups('UserDetails')]
    private array $roles = self::DEFAULT_ROLES;

    #[ORM\Column(options: ['default' => true])]
    #[Groups('UserDetails')]
    private bool $enabled = true;

    #[ORM\OneToMany(targetEntity: Dossier::class, mappedBy: 'author', orphanRemoval: true)]
    #[Groups('Dossier')]
    /** @var Collection<int, Dossier> */
    private Collection $dossiers;

    use LastUpdateTrait;

    public function __construct()
    {
        $this->dossiers = new ArrayCollection;
    }

    public function __toString(): string
    {
        return $this->getName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return $this->getUsername();
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): void
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getDossiers(): Collection
    {
        return $this->dossiers;
    }

    public function addDossier(Dossier $dossier): self
    {
        if (!$this->dossiers->contains($dossier)) {
            $this->dossiers[] = $dossier;
            $dossier->setAuthor($this);
        }

        return $this;
    }

    public function removeDossier(Dossier $dossier): self
    {
        if ($this->dossiers->contains($dossier)) {
            $this->dossiers->removeElement($dossier);
            // set the owning side to null (unless already changed)
            if ($dossier->getAuthor() === $this) {
                $dossier->setAuthor(null);
            }
        }

        return $this;
    }
}
