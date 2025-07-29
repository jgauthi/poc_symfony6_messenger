<?php
namespace App\Entity;

use App\Entity\Trait\LastUpdateTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: \App\Repository\ClientRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Client
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    #[Groups('Client')]
    private ?int $id;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 3, max: 255), Assert\NotBlank]
    #[Groups('Client')]
    private string $name;

    #[ORM\Column(length: 255)]
    #[Assert\Email, Assert\Length(min: 6, max: 255), Assert\NotBlank]
    #[Groups('ClientDetails')]
    private string $email;

    #[ORM\Column(length: 100, nullable: true, options: ['default' => null])]
    #[Groups('ClientDetails')]
    private ?string $address = null;

    #[ORM\Column(length: 50, nullable: true, options: ['default' => null])]
    #[Groups('ClientDetails')]
    private ?string $city = null;

    #[ORM\Column(length: 50, nullable: true, options: ['default' => null])]
    #[Groups('ClientDetails')]
    private ?string $country = null;

    #[ORM\Column(options: ['default' => true])]
    private bool $active = true;

    #[ORM\OneToMany(targetEntity: Dossier::class, mappedBy: 'client', orphanRemoval: true)]
    /** @var Collection<int, Dossier> */
    private Collection $dossier;

    use LastUpdateTrait;

    public function __construct()
    {
        $this->dossier = new ArrayCollection;
    }

    public function __toString(): string
    {
        return $this->getName();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    /** @return Collection<int, Dossier> */
    public function getDossier(): Collection
    {
        return $this->dossier;
    }

    public function addDossier(Dossier $dossier): self
    {
        if (!$this->dossier->contains($dossier)) {
            $this->dossier[] = $dossier;
            $dossier->setClient($this);
        }

        return $this;
    }

    public function removeDossier(Dossier $dossier): self
    {
        if ($this->dossier->contains($dossier)) {
            $this->dossier->removeElement($dossier);
            // set the owning side to null (unless already changed)
            if ($dossier->getClient() === $this) {
                $dossier->setClient(null);
            }
        }

        return $this;
    }
}
