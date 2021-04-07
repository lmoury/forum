<?php

namespace App\DataFixtures;

use App\Entity\ForumCommentaire;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ForumCommentairesFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $faker = Factory::create('fr_FR');
        // for ($i=0; $i < 100 ; $i++) {
        //     $commmentaires = new ForumCommentaire();
        //     $commmentaires
        //         ->setCommentaire($faker->sentences(3, true))
        //         ->setIdUser($faker->numberBetween(1, 4))
        //         ->setIdDiscussion(2)
        //         ->setDateCreation($faker->dateTime('now', null))
        //         ->setDateEdition($faker->dateTime('now', null))
        //         ;
        //         $manager->persist($commmentaires);
        // }
        // $product = new Product();
        // $manager->persist($product);

        //$manager->flush();
    }
}
