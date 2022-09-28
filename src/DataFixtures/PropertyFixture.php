<?php

namespace App\DataFixtures;

use App\Entity\Property;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class PropertyFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('en_EN');

        for($i=0;$i<100;$i++)
        {
            $property = new Property();
            $property->setTitle($faker->company)
                ->setDescription($faker->sentence(2, true))
                ->setSurface($faker->numberBetween(90,180))
                ->setRooms($faker->numberBetween(2,10))
                ->setBedrooms($faker->numberBetween(2,6))
                ->setFloors(($faker->numberBetween(1,6)))
                ->setPrice($faker->numberBetween(20000,900000))
                ->setHeat($faker->numberBetween(0, count(Property::Heat)-1))
                ->setCity($faker->city)
                ->setAdress($faker->address)
                ->setPostalCode($faker->postcode)
                ->setSold(false);

            $manager->persist($property);
        }
        $manager->flush();
    }
}
