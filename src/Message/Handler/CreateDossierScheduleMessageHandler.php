<?php
namespace App\Message\Handler;

use App\Entity\Dossier;
use App\Message\CreateScheduleDossierMessage;
use App\Repository\{ClientRepository, UserRepository};
use App\Service\LogFileService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CreateDossierScheduleMessageHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ClientRepository $clientRepository,
        private UserRepository $userRepository,
        private LogFileService $logFileService,
    ) {
    }

    public function __invoke(CreateScheduleDossierMessage $message): void
    {
        $admin = $this->userRepository->findOneBy(['username' => 'admin']);
        if (empty($admin)) {
            throw new \RuntimeException('Admin user not found');
        }

        $clients = $this->clientRepository->findBy(['active' => true], ['name' => 'ASC']);
        if (empty($clients)) {
            $this->logFileService->log('No active clients found, skipping dossier creation');

            return;
        }

        foreach ($clients as $client) {
            $type = $message->getType();
            $dossier = (new Dossier)
                ->setTitle(ucfirst($type).' for '.$client->getName())
                ->setContent(sprintf('This is a %s dossier for %s', $type, $client->getName()))
                ->setClient($client)
                ->setAuthor($admin)
            ;

            $client->addDossier($dossier);
            $this->entityManager->persist($dossier);
            $this->logFileService->log(sprintf('Created dossier %d for client %s', $dossier->getId(), $client->getName()));
        }

        $this->entityManager->flush();
    }
}
