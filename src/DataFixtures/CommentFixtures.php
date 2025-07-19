<?php
namespace App\DataFixtures;

use App\Entity\{Comment, Dossier, User};
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public const int NB_FIXTURE = 10;
    private \Faker\Generator $faker;

    public function __construct()
    {
        $this->faker = FakerFactory::create();
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < self::NB_FIXTURE; ++$i) {
            /** @var Dossier $randomDossier */
            $randomDossier = $this->getReference(DossierFixtures::getRandomReference(), Dossier::class);

            /** @var User $randomUsername */
            $randomUsername = $this->getReference(UserFixtures::getRandomReference(), User::class);

            $comment = (new Comment)
                ->setContent($this->faker->text())
                ->setDossier($randomDossier)
                ->setAuthor($randomUsername)
            ;

            $manager->persist($comment);
            $this->setReference("comment_$i", $comment);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ClientFixtures::class,
            DossierFixtures::class,
            UserFixtures::class,
        ];
    }

    public static function getRandomReference(): string
    {
        return 'dossier_'.rand(0, self::NB_FIXTURE - 1);
    }
}
