<?php
namespace App\Entity;

use App\Entity\Trait\LastUpdateTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: \App\Repository\CategoryRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[Vich\Uploadable]
class Category
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    #[Groups('Category')]
    private ?int $id;

    #[ORM\Column(length: 100)]
    #[Groups('Category')]
    private string $title;

    #[ORM\Column(length: 255, nullable: true, options: ['default' => null])]
    #[Groups('CategoryDetails')]
    private ?string $image = null;

    #[Vich\UploadableField(mapping: 'category_images', fileNameProperty: 'image')]
    #[Groups('CategoryDetails')]
    public ?File $file = null;

    #[ORM\ManyToMany(targetEntity: Dossier::class, inversedBy: 'categories')]
    /** @var Collection<int, Dossier> */
    private Collection $dossier;

    use LastUpdateTrait;

    public function __construct()
    {
        $this->dossier = new ArrayCollection;
    }

    public function __toString(): string
    {
        return $this->getTitle();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function getImageRelativePath(): ?string
    {
        return ($this->image) ? '/images/category/'.$this->image : null;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function setImageFile(?File $image = null): self
    {
        $this->file = $image;

        return $this;
    }

    public function getImageFile(): ?File
    {
        return $this->file;
    }

    /**
     * @return Collection<int, Dossier>
     */
    public function getDossier(): Collection
    {
        return $this->dossier;
    }

    public function addDossier(Dossier $dossier): self
    {
        if (!$this->dossier->contains($dossier)) {
            $this->dossier[] = $dossier;
        }

        return $this;
    }

    public function removeDossier(Dossier $dossier): self
    {
        if ($this->dossier->contains($dossier)) {
            $this->dossier->removeElement($dossier);
        }

        return $this;
    }
}
