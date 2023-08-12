<?php
namespace App\Repository;

use App\Entity\Dossier;
use App\Entity\Enum\DossierStatusEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Dossier|null find($id, $lockMode = null, $lockVersion = null)
 * @method Dossier|null findOneBy(array $criteria, array $orderBy = null)
 * @method Dossier[]    findAll()
 * @method Dossier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DossierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Dossier::class);
    }

    public function queryByStatus(?DossierStatusEnum $status): Query
    {
        if (null === $status) {
            $status = DossierStatusEnum::ACTIVE;
        }

        return $this->createQueryBuilder('dossier')
            ->where('dossier.status = :status')
            ->setParameter('status', $status->value)
            ->getQuery()
        ;
    }
}
