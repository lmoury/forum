<?php

namespace App\DataFixtures;

use App\Entity\ForumCategorie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class ForumCategorieFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $categ1 = new ForumCategorie();
        $categ1->setCategorie('Forums généraux');
        $categ1->setIcon('fa fa-home');
        $categ1->setOrdre(10);
        $manager->persist($categ1);

        $categ2 = new ForumCategorie();
        $categ2->setCategorie('Informatique');
        $categ2->setIcon('fa fa-laptop');
        $categ2->setOrdre(20);
        $manager->persist($categ2);

        $categ3 = new ForumCategorie();
        $categ3->setCategorie('Programmation');
        $categ3->setIcon('fa fa-code');
        $categ3->setOrdre(30);
        $manager->persist($categ3);

        $categ4 = new ForumCategorie();
        $categ4->setCategorie('Présentation des membres');
        $categ4->setParent(1);
        $categ4->setIcon('fa fa-users');
        $categ4->setOrdre(10);
        $manager->persist($categ4);

        $categ5 = new ForumCategorie();
        $categ5->setCategorie('Linux');
        $categ5->setParent(2);
        $categ5->setIcon('fa fa-linux');
        $categ5->setOrdre(10);
        $manager->persist($categ5);

        $categ6 = new ForumCategorie();
        $categ6->setCategorie('HTML');
        $categ6->setParent(3);
        $categ6->setIcon('fa fa-html5');
        $categ6->setOrdre(10);
        $manager->persist($categ6);

        $categ7 = new ForumCategorie();
        $categ7->setCategorie('JavaScript');
        $categ7->setParent(3);
        $categ7->setIcon('fa fa-code');
        $categ7->setOrdre(20);
        $manager->persist($categ7);

        $categ8 = new ForumCategorie();
        $categ8->setCategorie('PHP');
        $categ8->setParent(3);
        $categ8->setIcon('fa fa-code');
        $categ8->setOrdre(30);
        $manager->persist($categ8);

        $manager->flush();
        $this->addReference('categ4', $categ4);
        $this->addReference('categ5', $categ5);
        $this->addReference('categ6', $categ6);
        $this->addReference('categ7', $categ7);
        $this->addReference('categ8', $categ8);
    }

    public function getOrder() {
        return 3;
    }
}
