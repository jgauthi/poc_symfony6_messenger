<?php
namespace App\Entity\Trait;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

// Entity require attribute for work: #[ORM\HasLifecycleCallbacks]
trait LastUpdateTrait
{
    #[ORM\Column(type: Types::DATETIME_MUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private \DateTimeInterface $lastUpdate;

    public function getLastUpdate(): \DateTimeInterface
    {
        return $this->lastUpdate;
    }

    public function setLastUpdate(\DateTimeInterface $lastUpdate): self
    {
        $this->lastUpdate = $lastUpdate;

        return $this;
    }

    #[ORM\PrePersist, ORM\PreUpdate]
    public function persistLastUpdate(): void
    {
        $this->lastUpdate = new \DateTime;
    }
}
