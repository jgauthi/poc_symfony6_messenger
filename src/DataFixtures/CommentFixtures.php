<?php
namespace App\DataFixtures;

use App\Entity\{Comment, Dossier, User};
use App\Message\SendComment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Symfony\Component\Messenger\MessageBusInterface;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public const NB_FIXTURE = 10;
    private \Faker\Generator $faker;

    public function __construct(private MessageBusInterface $bus)
    {
        $this->faker = FakerFactory::create();
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < self::NB_FIXTURE; ++$i) {
            /** @var Dossier $randomDossier */
            $randomDossier = rand(0, DossierFixtures::NB_FIXTURE - 1);
            $randomDossier = $this->getReference("dossier_{$randomDossier}"); /** @var Dossier $randomDossier */
            $randomUsername = array_rand(UserFixtures::USERS, 1);
            $randomUsername = $this->getReference("user_{$randomUsername}"); /** @var User $randomUsername */
            $comment = (new Comment)
                ->setContent($this->faker->text())
                ->setDossier($randomDossier)
                ->setAuthor($randomUsername)
            ;

            $manager->persist($comment);
            $this->setReference("comment_$i", $comment);
        }

        $manager->flush();

        // Add Comments to Symfony BUS
        for ($i = 0; $i < self::NB_FIXTURE; ++$i) {
            /** @var Comment $comment */
            $comment = $this->getReference("comment_$i");
            if (empty($comment->getId())) {
                continue;
            }

            $this->bus->dispatch(new SendComment($comment->getId()));
        }
    }

    public function getDependencies(): array
    {
        return [
            ClientFixtures::class,
            DossierFixtures::class,
            UserFixtures::class,
        ];
    }
}
