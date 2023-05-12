<?php
namespace App\DataFixtures;

use App\Entity\{Client, Dossier, User};
use App\Entity\Enum\DossierStatusEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;

class DossierFixtures extends Fixture implements DependentFixtureInterface
{
    public const NB_FIXTURE = 20;
    private \Faker\Generator $faker;

    public function __construct()
    {
        $this->faker = FakerFactory::create();
    }

    public function load(ObjectManager $manager): void
    {
        $fctRandomStatus = function (): DossierStatusEnum {
            if ($this->faker->boolean(60)) {
                return DossierStatusEnum::ACTIVE;
            }

            $count = count(DossierStatusEnum::cases()) - 1;

            return DossierStatusEnum::cases()[rand(0, $count)];
        };

        for ($i = 0; $i < self::NB_FIXTURE; ++$i) {
            /** @var Client $randomClient */
            $randomClient = rand(0, ClientFixtures::NB_FIXTURE - 1);
            $randomClient = $this->getReference("client_{$randomClient}"); /** @var Client $randomClient */
            $randomUsername = array_rand(UserFixtures::USERS, 1);
            $randomUsername = $this->getReference("user_{$randomUsername}"); /** @var User $randomUsername */
            $dossier = (new Dossier)
                ->setTitle(ucfirst(implode(' ', (array) $this->faker->unique()->words(rand(2, 4)))))
                ->setContent($this->faker->text())
                ->setStatus($fctRandomStatus())
                ->setClient($randomClient)
                ->setAuthor($randomUsername)
            ;

            $manager->persist($dossier);
            $this->setReference("dossier_$i", $dossier);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ClientFixtures::class,
            UserFixtures::class,
        ];
    }
}
