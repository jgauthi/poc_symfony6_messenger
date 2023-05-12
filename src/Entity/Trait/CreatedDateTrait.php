<?php
namespace App\Entity\Trait;

use Doctrine\ORM\Mapping as ORM;

// Entity require attribute for work: #[ORM\HasLifecycleCallbacks]
trait CreatedDateTrait
{
    #[ORM\Column(options: ['default' => 'CURRENT_TIMESTAMP'])]
    private \DateTimeImmutable $createdDate;

    public function getCreatedDate(): \DateTimeImmutable
    {
        return $this->createdDate;
    }

    public function setCreatedDate(\DateTimeImmutable $createdDate): self
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    #[ORM\PrePersist]
    public function persistCreatedDate(): void
    {
        $this->createdDate = new \DateTimeImmutable;
    }
}
