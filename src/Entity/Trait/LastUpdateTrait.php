<?php
namespace App\Entity\Trait;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

// Entity require attribute for work: #[ORM\HasLifecycleCallbacks]
trait LastUpdateTrait
{
    #[ORM\Column(type: Types::DATETIME_MUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    #[Groups('Details')]
    private \DateTime $lastUpdate;

    public function getLastUpdate(): \DateTime
    {
        return $this->lastUpdate;
    }

    public function setLastUpdate(\DateTime $lastUpdate): self
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
