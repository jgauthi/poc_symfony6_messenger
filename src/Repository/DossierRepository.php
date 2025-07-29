<?php
namespace App\Repository;

use App\Entity\Dossier;
use App\Entity\Enum\DossierStatusEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
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

    public function queryBuilderByStatus(?DossierStatusEnum $status, string $operation = 'eq'): QueryBuilder
    {
        $qb = $this->createQueryBuilder('dossier');
        if (!method_exists($qb->expr(), $operation)) {
            throw new \InvalidArgumentException(sprintf('Operation "%s" is not supported.', $operation));
        } elseif (null === $status) {
            $status = DossierStatusEnum::ACTIVE;
        }

        $qb
            ->where($qb->expr()->$operation('dossier.status', ':status'))
            ->setParameter('status', $status->value)
        ;

        return $qb;
    }
}
