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
        $categ1->setLocked(0);
        $manager->persist($categ1);

        $categ2 = new ForumCategorie();
        $categ2->setCategorie('Informatique');
        $categ2->setIcon('fa fa-laptop');
        $categ2->setOrdre(20);
        $categ2->setLocked(0);
        $manager->persist($categ2);

        $categ3 = new ForumCategorie();
        $categ3->setCategorie('Programmation');
        $categ3->setIcon('fa fa-code');
        $categ3->setOrdre(30);
        $categ3->setLocked(0);
        $manager->persist($categ3);

        $categ4 = new ForumCategorie();
        $categ4->setCategorie('Discussions générales');
        $categ4->setParent(1);
        $categ4->setIcon('fa fa-comments');
        $categ4->setOrdre(10);
        $categ4->setLocked(0);
        $manager->persist($categ4);

        $categ5 = new ForumCategorie();
        $categ5->setCategorie('Présentation des membres');
        $categ5->setParent(1);
        $categ5->setIcon('fa fa-users');
        $categ5->setOrdre(20);
        $categ5->setLocked(0);
        $manager->persist($categ5);

        $categ6 = new ForumCategorie();
        $categ6->setCategorie('Infographie');
        $categ6->setParent(1);
        $categ6->setIcon('fa fa-paint-brush');
        $categ6->setOrdre(30);
        $categ6->setLocked(0);
        $manager->persist($categ6);

        $categ7 = new ForumCategorie();
        $categ7->setCategorie('PHP');
        $categ7->setParent(3);
        $categ7->setIcon('icon-php-alt');
        $categ7->setOrdre(30);
        $categ7->setLocked(0);
        $manager->persist($categ7);

        $categ8 = new ForumCategorie();
        $categ8->setCategorie('Windows');
        $categ8->setParent(2);
        $categ8->setIcon('fa fa-windows');
        $categ8->setOrdre(10);
        $categ8->setLocked(0);
        $manager->persist($categ8);

        $categ9 = new ForumCategorie();
        $categ9->setCategorie('Mac OS X');
        $categ9->setParent(2);
        $categ9->setIcon('fa fa-apple');
        $categ9->setOrdre(20);
        $categ9->setLocked(0);
        $manager->persist($categ9);

        $categ10 = new ForumCategorie();
        $categ10->setCategorie('Linux');
        $categ10->setParent(2);
        $categ10->setIcon('fa fa-linux');
        $categ10->setOrdre(30);
        $categ10->setLocked(0);
        $manager->persist($categ10);

        $categ11 = new ForumCategorie();
        $categ11->setCategorie('Mobiles & Tablettes');
        $categ11->setParent(2);
        $categ11->setIcon('icon-mobile-device');
        $categ11->setOrdre(40);
        $categ11->setLocked(0);
        $manager->persist($categ11);

        $categ12 = new ForumCategorie();
        $categ12->setCategorie('Hardware');
        $categ12->setParent(2);
        $categ12->setIcon('fa fa-desktop');
        $categ12->setOrdre(50);
        $categ12->setLocked(0);
        $manager->persist($categ12);

        $categ13 = new ForumCategorie();
        $categ13->setCategorie('Logiciels & applications');
        $categ13->setParent(2);
        $categ13->setIcon('fa fa-save');
        $categ13->setOrdre(60);
        $categ13->setLocked(0);
        $manager->persist($categ13);

        $categ14 = new ForumCategorie();
        $categ14->setCategorie('Raspberry Pi');
        $categ14->setParent(2);
        $categ14->setIcon('icon-raspberrypi');
        $categ14->setOrdre(70);
        $categ14->setLocked(0);
        $manager->persist($categ14);

        $categ15 = new ForumCategorie();
        $categ15->setCategorie('Autres');
        $categ15->setParent(2);
        $categ15->setIcon('fa fa-plus-circle');
        $categ15->setOrdre(100);
        $categ15->setLocked(0);
        $manager->persist($categ15);

        $categ16 = new ForumCategorie();
        $categ16->setCategorie('HTML & CSS');
        $categ16->setParent(3);
        $categ16->setIcon('fa fa-html5');
        $categ16->setOrdre(10);
        $categ16->setLocked(0);
        $manager->persist($categ16);

        $categ17 = new ForumCategorie();
        $categ17->setCategorie('SQL');
        $categ17->setParent(3);
        $categ17->setIcon('icon-mysql-alt');
        $categ17->setOrdre(20);
        $categ17->setLocked(0);
        $manager->persist($categ17);

        $categ18 = new ForumCategorie();
        $categ18->setCategorie('Premium');
        $categ18->setIcon('fa fa-diamond');
        $categ18->setOrdre(100);
        $categ18->setLocked(0);
        $manager->persist($categ18);

        $categ19 = new ForumCategorie();
        $categ19->setCategorie('JavaScript');
        $categ19->setParent(3);
        $categ19->setIcon('icon-javascript-alt');
        $categ19->setOrdre(40);
        $categ19->setLocked(0);
        $manager->persist($categ19);

        $categ20 = new ForumCategorie();
        $categ20->setCategorie('Java');
        $categ20->setParent(3);
        $categ20->setIcon('icon-java-bold');
        $categ20->setOrdre(50);
        $categ20->setLocked(0);
        $manager->persist($categ20);

        $categ21 = new ForumCategorie();
        $categ21->setCategorie('Python');
        $categ21->setParent(3);
        $categ21->setIcon('icon-python');
        $categ21->setOrdre(60);
        $categ21->setLocked(0);
        $manager->persist($categ21);

        $categ22 = new ForumCategorie();
        $categ22->setCategorie('Autres');
        $categ22->setParent(3);
        $categ22->setIcon('fa fa-plus-circle');
        $categ22->setOrdre(100);
        $categ22->setLocked(0);
        $manager->persist($categ22);

        $categ23 = new ForumCategorie();
        $categ23->setCategorie('Informatique');
        $categ22->setParent(18);
        $categ23->setIcon('fa fa-laptop');
        $categ23->setOrdre(20);
        $categ23->setLocked(0);
        $manager->persist($categ23);

        $categ24 = new ForumCategorie();
        $categ24->setCategorie('Programmation');
        $categ22->setParent(18);
        $categ24->setIcon('fa fa-code');
        $categ24->setOrdre(30);
        $categ24->setLocked(0);
        $manager->persist($categ24);

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
