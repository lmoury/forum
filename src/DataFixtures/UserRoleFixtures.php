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

        $manager->flush();
        $this->addReference('role1', $role1);
        $this->addReference('role2', $role2);
        $this->addReference('role3', $role3);
    }

    public function getOrder() {
        return 1;
    }
}
