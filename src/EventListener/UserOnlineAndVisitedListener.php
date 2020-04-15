<?php
namespace App\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Security;
use Doctrine\DBAL\Driver\Connection;

class UserOnlineAndVisitedListener
{
    private $em;

    private $security;


    public function __construct(EntityManagerInterface $em, Security $security)
    {
        $this->em = $em;
        $this->security = $security;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {

        if ($this->security->getUser() !== null) {
            $usertest = $this->security->getUser();
            $usertest->setDateVisite(new \DateTime());
            $this->em->flush();
        }

    }
}
