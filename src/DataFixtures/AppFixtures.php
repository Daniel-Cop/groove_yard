<?php

namespace App\DataFixtures;


use App\Entity\Address;
use App\Entity\Album;
use App\Entity\Intention;
use App\Entity\Inventory;
use App\Entity\User;
use App\Service\CoordinateApi;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Condition;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private const CONDITIONS =
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

    private const INTENTIONS = ['Owned', 'To Sell', 'Want'];


    private function JSONTranslate($file)
    {
        $filename = __DIR__ . '/' . $file;
        
        if (!file_exists($filename)) {
            return null;
        }

        $fileContent = file_get_contents($filename);
        if ($fileContent === false) {
            return null;
        }

        $data = json_decode($fileContent, true);
        if ($data === null) {
            return null;
        }

        return $data;

    }

    public function load(ObjectManager $manager): void
    {

        $faker = \Faker\Factory::create('en_US');
        $conditionList = [];
        $intentionList = [];
        $userList = [];
        $albumList = [];
        $addressList = [];

        foreach (self::CONDITIONS as $c) {

            $condition = new Condition();
            $condition->setName($c["name"])
                ->setDescription($c["desc"]);

            $conditionList[] = $condition;

            $manager->persist($condition);

        }

        foreach (self::INTENTIONS as $i) {

            $intention = new Intention();
            $intention->setName($i);

            $intentionList[] = $intention;

            $manager->persist($intention);

        }
        for ($i = 0; $i < 21; $i++) {
            $address = new Address();
            $address
            ->setLatitude($faker->latitude($min = 43, $max = 49))
            ->setLongitude($faker->longitude($min = 0.5, $max = 5.8))
            ->setStreet($faker->streetName())
            ->setNumber($faker->numerify('##'))
            ->setPostalCode($faker->postcode())
            ->setCity($faker->city());
            // I tried to create more real fixture by creating a random latitude and longitude (but always situated in France)
            // and then to use the reverse api to find the address from the coordinates. Unluckly the research was not always 
            // precise enough to find an address 100% of the times, so i went via faker to create a fake address attached to real
            // french coordinates (what the app really need is coordinate anyway)

            $addressList[] = $address;

            $manager->persist($address);

        }
        
        for ($i = 0; $i < 20; $i++) {
            $user = new User();
            $user->setEmail($faker->safeEmail())
                ->setUsername($faker->userName())
                ->setRoles(['ROLE_USER'])
                ->setPassword('test')
                ->setImage('profile-'.$faker->randomElement(['1', '2', '3', '4']).'png')
                ->setAddress($addressList[$i]);

            $userList[] = $user;

            $manager->persist($user);
        }

        $admin = new User();
        $admin->setEmail('admin@groove.com')
            ->setUsername('Admin')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword('admin')
            ->setImage('profile-'.$faker->randomElement(['1', '2', '3', '4']).'png')
            ->setAddress($addressList[20]);

        $manager->persist($admin);

        $data = $this->JSONTranslate('records.json');

        foreach ($data as $d) {
            $record = new Album();
            $record->setTitle($d['title'])
            ->setArtist($d['artist'])
            ->setYear($d['year'])
            ->setImage('vinyl-'.$faker->randomElement(['1', '2', '3', '4', '5']).'.png');

            $albumList[] = $record;
            $manager->persist($record);
        }

        for ($i = 0; $i < 55; $i++) {
            $inventory = new Inventory();
            $inventory->setUser($faker->randomElement($userList))
            ->setAlbum($faker->randomElement($albumList))
            ->setIntention($faker->randomElement($intentionList))
            ->setStatus($faker->randomElement($conditionList))
            ->setCreatedAt($faker->dateTimeBetween('-1 year'));

            $manager->persist($inventory);

        }

        $manager->flush();
    }
}
