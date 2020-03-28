<?php
namespace App\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Whosonline;
use App\Repository\WhosonlineRepository;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Security;
use Doctrine\DBAL\Driver\Connection;

class WhosonlineListener
{
    private $em;

    private $connection;

    private $security;

    private $repository;

    public function __construct(EntityManagerInterface $em, Security $security, Connection $connection, WhosonlineRepository $repository)
    {
        $this->em = $em;
        $this->security = $security;
        $this->connection = $connection;
        $this->repository = $repository;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {


        if ($this->security->getUser() !== null) {

            $time_max = time() - (60 * 5);
            $vide = $this->repository->findAll();

            if($vide != NULL) {
                $this->connection->exec('DELETE FROM whosonline WHERE online_time < '.$time_max);
            }

            $usertest = $this->security->getUser();
            $repo = $this->repository->getWhosonlineUser($usertest);
            if($repo == null) {

                $test = new Whosonline();
                $test->setOnline($usertest);
                $test->setOnlineTime(time());
                $this->em->persist($test);
                $this->em->flush();
            }

        }
        elseif ($this->security->getUser() == null) {
            
            $ip = $_SERVER['REMOTE_ADDR'];
            $repo = $this->repository->getWhosonlineIp($ip);
            if($repo == null) {
                $test = new Whosonline();
                $test->setOnlineIp($ip);
                $test->setOnlineTime(time());
                $this->em->persist($test);
                $this->em->flush();
            }
        }

    }
}
