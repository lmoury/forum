<?php

namespace App\DataFixtures;

use App\Entity\UserRole;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class UserRoleFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $role1 = new UserRole();
        $role1->setNom('Membre');
        $role1->setRole('ROLE_USER');
        $role1->setLevel(10);
        $manager->persist($role1);

        $role2 = new UserRole();
        $role2->setNom('Modérateur');
        $role2->setRole('ROLE_MODERATEUR');
        $role2->setLevel(3);
        $manager->persist($role2);

        $role3 = new UserRole();
        $role3->setNom('Administrateur');
        $role3->setRole('ROLE_ADMIN');
        $role3->setLevel(1);
        $manager->persist($role3);

        $role4 = new UserRole();
        $role4->setNom('Assistant');
        $role4->setRole('ROLE_ASSIST');
        $role4->setLevel(5);
        $manager->persist($role4);

        $role5 = new UserRole();
        $role5->setNom('Banni');
        $role5->setRole('ROLE_BANNI');
        $role5->setLevel(50);
        $manager->persist($role5);

        $manager->flush();
        $this->addReference('role1', $role1);
        $this->addReference('role2', $role2);
        $this->addReference('role3', $role3);
    }

    public function getOrder() {
        return 1;
    }
}
