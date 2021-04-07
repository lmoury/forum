<?php

namespace App\DataFixtures;

use App\Entity\Config;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class ConfigFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $config1 = new Config();
        $config1->setNom('titre du site');
        $manager->persist($config1);

        $config2 = new Config();
        $config2->setNom('URL du site');
        $manager->persist($config2);

        $config3 = new Config();
        $config3->setNom('Logo navbar');
        $manager->persist($config3);

        $manager->flush();
    }

    public function getOrder() {
        return 5;
    }
}
