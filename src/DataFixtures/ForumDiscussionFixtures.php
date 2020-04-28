<?php

namespace App\DataFixtures;

use App\Entity\ForumDiscussion;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Faker\Factory;


class ForumDiscussionFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        for ($i=0; $i < 120 ; $i++) {
            $count = rand(4, 8);
            $countU = rand(1, 9);
            $discussions = new ForumDiscussion();
            $discussions
                ->setTitre($faker->words(3, true))
                ->setMessage($faker->text(1200))
                ->setAuteur($manager->merge($this->getReference('user'.$countU)))
                ->setCategorie($manager->merge($this->getReference('categ'.$count)))
                ->setDateCreation($faker->dateTime('now', null))
                ->setDateEdition($faker->dateTime('now', null))
                ->setDateNewCom($faker->dateTime('now', null))
                ->setLocked(0)
                ->setImportant(0)
                ;
                $manager->persist($discussions);
        }

        $manager->flush();
    }

    public function getOrder() {
        return 4;
    }

}
