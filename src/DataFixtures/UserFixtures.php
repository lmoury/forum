<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture implements OrderedFixtureInterface
{

    private $encoder;


    public function __construct(UserPasswordEncoderInterface $encoder)
    {
            $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $user1 = new User();
        $user1->setUsername('admin');
        $user1->setEmail('admin@gmail.com');
        $user1->setSexe(1);
        $user1->setPassword($this->encoder->encodePassword($user1, 'admin'));
        $user1->setAvatar('myAvatar.png');
        $user1->setDateNaissance(new \DateTime('09/06/1988'));
        $user1->setRole($manager->merge($this->getReference('role3')));
        $manager->persist($user1);

        $user2 = new User();
        $user2->setUsername('modo');
        $user2->setEmail('modo@gmail.com');
        $user2->setSexe(1);
        $user2->setPassword($this->encoder->encodePassword($user2, 'modo'));
        $user2->setAvatar('avatarDefault.png');
        $user2->setDateNaissance(new \DateTime('06/04/2014'));
        $user2->setRole($manager->merge($this->getReference('role2')));
        $manager->persist($user2);

        $user3 = new User();
        $user3->setUsername('membre');
        $user3->setEmail('membre@gmail.com');
        $user3->setSexe(1);
        $user3->setPassword($this->encoder->encodePassword($user3, 'membre'));
        $user3->setAvatar('avatarDefault.png');
        $user3->setDateNaissance(new \DateTime('06/04/2014'));
        $user3->setRole($manager->merge($this->getReference('role1')));
        $manager->persist($user3);

        $user4 = new User();
        $user4->setUsername('azerty4');
        $user4->setEmail('azerty3@gmail.com');
        $user4->setSexe(1);
        $user4->setPassword($this->encoder->encodePassword($user4, 'test'));
        $user4->setAvatar('avatarDefault.png');
        $user4->setDateNaissance(new \DateTime('06/04/2014'));
        $user4->setRole($manager->merge($this->getReference('role1')));
        $manager->persist($user4);

        $user5 = new User();
        $user5->setUsername('azerty5');
        $user5->setEmail('azerty5@gmail.com');
        $user5->setSexe(1);
        $user5->setPassword($this->encoder->encodePassword($user5, 'test'));
        $user5->setAvatar('avatarDefault.png');
        $user5->setDateNaissance(new \DateTime('06/04/2014'));
        $user5->setRole($manager->merge($this->getReference('role1')));
        $manager->persist($user5);

        $user6 = new User();
        $user6->setUsername('azerty6');
        $user6->setEmail('azerty6@gmail.com');
        $user6->setSexe(1);
        $user6->setPassword($this->encoder->encodePassword($user6, 'test'));
        $user6->setAvatar('avatarDefault.png');
        $user6->setDateNaissance(new \DateTime('06/04/2014'));
        $user6->setRole($manager->merge($this->getReference('role1')));
        $manager->persist($user6);

        $user7 = new User();
        $user7->setUsername('azerty7');
        $user7->setEmail('azerty7@gmail.com');
        $user7->setSexe(1);
        $user7->setPassword($this->encoder->encodePassword($user7, 'test'));
        $user7->setAvatar('avatarDefault.png');
        $user7->setDateNaissance(new \DateTime('06/04/2014'));
        $user7->setRole($manager->merge($this->getReference('role1')));
        $manager->persist($user7);

        $user8 = new User();
        $user8->setUsername('azerty8');
        $user8->setEmail('azerty8@gmail.com');
        $user8->setSexe(1);
        $user8->setPassword($this->encoder->encodePassword($user8, 'test'));
        $user8->setAvatar('avatarDefault.png');
        $user8->setDateNaissance(new \DateTime('06/04/2014'));
        $user8->setRole($manager->merge($this->getReference('role1')));
        $manager->persist($user8);

        $user9 = new User();
        $user9->setUsername('azerty9');
        $user9->setEmail('azerty9@gmail.com');
        $user9->setSexe(1);
        $user9->setPassword($this->encoder->encodePassword($user9, 'test'));
        $user9->setAvatar('avatarDefault.png');
        $user9->setDateNaissance(new \DateTime('06/09/2019'));
        $user9->setRole($manager->merge($this->getReference('role1')));
        $manager->persist($user9);

        $user10 = new User();
        $user10->setUsername('azerty10');
        $user10->setEmail('azerty10@gmail.com');
        $user10->setSexe(1);
        $user10->setPassword($this->encoder->encodePassword($user10, 'test'));
        $user10->setAvatar('avatarDefault.png');
        $user10->setDateNaissance(new \DateTime('06/04/2014'));
        $user10->setRole($manager->merge($this->getReference('role1')));
        $manager->persist($user10);


        $manager->flush();
        $this->addReference('user1', $user1);
        $this->addReference('user2', $user2);
        $this->addReference('user3', $user3);
        $this->addReference('user4', $user4);
        $this->addReference('user5', $user5);
        $this->addReference('user6', $user6);
        $this->addReference('user7', $user7);
        $this->addReference('user8', $user8);
        $this->addReference('user9', $user9);
        $this->addReference('user10', $user10);

    }

    public function getOrder() {
        return 2;
    }
}
