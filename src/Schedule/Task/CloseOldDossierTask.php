<?php
namespace App\Schedule\Task;

use App\Entity\Dossier;
use App\Entity\Enum\DossierStatusEnum;
use App\Repository\DossierRepository;
use App\Service\LogFileService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Scheduler\Attribute\AsCronTask;

#[AsCronTask('0 8-20 * * 1-5')]
class CloseOldDossierTask
{
    private const string EXPIRATION_DATE = '-2 months';

    public function __construct(
        private DossierRepository $dossierRepository,
        private EntityManagerInterface $em,
        private LogFileService $logFileService,
    ) {
    }

    public function __invoke(): void
    {
        $expirationThreshold = new \DateTimeImmutable(self::EXPIRATION_DATE);
        $query = $this->dossierRepository->queryBuilderByStatus(DossierStatusEnum::CLOSED, 'lt');
        $query
            ->andWhere('dossier.createdDate < :expirationDate')
            ->setParameter('expirationDate', $expirationThreshold)
        ;

        /** @var Dossier[] $dossiers */
        $dossiers = $query->getQuery()->getResult();
        if (empty($dossiers)) {
            $this->logFileService->log('No old dossiers to close');

            return;
        }

        foreach ($dossiers as $dossier) {
            $this->logFileService->log(sprintf('Closing dossier %d: %s', $dossier->getId(), $dossier->getTitle()));
            $dossier->setStatus(DossierStatusEnum::CLOSED);
        }

        $this->em->flush();
    }
}
