<?php

namespace App\DataFixtures;

use App\Entity\Album;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Condition;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private const CONDITIONS_LIST =
        [
            [
                'name' => "Mint",
                'desc' => "Absolutely perfect in every way. Certainly never been played, possibly even still sealed."
            ],
            [
                'name' => "Near Mint",
                'desc' => "A nearly perfect record. The vinyl will play perfectly, with no imperfections during playback. The record should show no obvious signs of wear. The sleeve should have no more than the most minor defects, such as any sign of slight handling. The LP cover should have no creases, folds, seam splits, cut-out holes, or other noticeable similar defects."
            ],
            [
                'name' => "Very Good",
                'desc' => "The record show some signs that it was played and otherwise handled by a previous owner who took good care of it. Defects are more of a cosmetic nature, not affecting the actual playback as a whole. Record surfaces may show some signs of wear and may have slight scuffs or very light scratches that don't affect the listening experiences. The sleeves have some slight wear, slightly turned-up corners, or a slight seam split. The LP cover may have slight signs of wear, and may be marred by a cut-out hole, indentation, or cut corner."
            ],
            [
                'name' => "Good",
                'desc' => "The record can be played through without skipping, but it will have significant surface noise, scratches, and visible groove wear, affecting the playback. Cover or sleeve will have seam splits. Tape, writing, ring wear, or other defects could be present."
            ],
            [
                'name' => "Poor",
                'desc' => "The record is cracked, badly warped, and can't be played through without skipping or repeating. The picture sleeve is water damaged, split on all three seams and heavily marred by wear and writing. The LP cover barely keeps the LP inside it. Inner sleeves are fully split, crinkled, and written upon."
            ],
        ];


    public function load(ObjectManager $manager): void
    {

        $faker = \Faker\Factory::create('en_US');
        $conditionList = [];
        $userList = [];

        foreach (self::CONDITIONS_LIST as $cond) {

            $condition = new Condition();
            $condition->setName($cond["name"])
                ->setDescription($cond["desc"]);

            $conditionList[] = $condition;

            $manager->persist($condition);

        }

        for ($i = 0; $i < 20; $i++) {
            $user = new User();
            $user->setEmail($faker->safeEmail())
                ->setUsername($faker->userName())
                ->setRoles(['ROLE_USER'])
                ->setPassword('test');

            $userList[] = $user;

            $manager->persist($user);
        }

        $admin = new User();
        $admin->setEmail('admin@groove.com')
            ->setUsername('Admin')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword('admin');

        $manager->persist($admin);

        for ($i = 0; $i < 100; $i++) {
            $album = new Album;
            $album->setTitle($faker->sentence($faker->numberBetween(1, 5)))
                ->setArtist($faker->firstName($gender = 'male' | 'female'))
                ->setImage($faker->url())
                ->setYear(strval($faker->numberBetween(1948, 2024)))
                ->setDescription($faker->realTextBetween($minNbChars = 160, $maxNbChars = 200, $indexSize = 2))
                ->setState($faker->randomElement($conditionList))
                ->setUser($faker->randomElement($userList));

            $manager->persist($album);
        }

        $manager->flush();
    }
}
