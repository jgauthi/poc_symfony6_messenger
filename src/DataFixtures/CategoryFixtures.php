<?php
namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Dossier;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CategoryFixtures extends Fixture implements DependentFixtureInterface
{
    public const int NB_FIXTURE = 7;
    private \Faker\Generator $faker;

    public function __construct(private string $imagesPublic, private string $imagesCategoryPublic)
    {
        $this->faker = FakerFactory::create();
    }

    /**
     * @throws \ErrorException
     */
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < self::NB_FIXTURE; ++$i) {
            $original = $this->faker->randomElement([
                'imageFixture1.jpg',
                'imageFixture2.jpg',
                'imageFixture3.jpg',
                'imageFixture4.jpg',
                'imageFixture5.jpg',
            ]);
            $imageModel = $this->imagesPublic.'/'.$original;
            $imagePath = $this->imagesCategoryPublic.'/'.rand().'.jpg';
            if (!is_readable($imageModel)) {
                throw new \ErrorException("Image {$imageModel} not readable.");
            } elseif (!copy($imageModel, $imagePath)) {
                throw new \ErrorException("Copy file {$imageModel} failed.");
            }

            $file = new UploadedFile(
                $imagePath,
                basename($imagePath),
                'image/jpg',
                null,
                true // Set test mode true !!! " Local files are used in test mode hence the code should not enforce HTTP uploads."
            );

            $category = (new Category)
                ->setTitle($this->faker->unique()->streetName())
                ->setImageFile($file)
            ;

            $alreadyUse = [];
            for ($j = 0; $j < rand(1, 5); ++$j) {
                $randomDossier = DossierFixtures::getRandomReference();
                if (\in_array($randomDossier, $alreadyUse, true)) {
                    continue;
                }

                $alreadyUse[] = $randomDossier;
                $category->addDossier($this->getReference($randomDossier, Dossier::class));
            }

            $manager->persist($category);
            $this->setReference("category_$i", $category);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            DossierFixtures::class,
        ];
    }

    public static function getRandomReference(): string
    {
        return 'category_'.rand(0, self::NB_FIXTURE - 1);
    }
}
