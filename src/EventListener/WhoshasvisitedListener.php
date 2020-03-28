<?php
namespace App\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Whoshasvisited;
use App\Repository\WhoshasvisitedRepository;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Security;
use Doctrine\DBAL\Driver\Connection;

class WhoshasvisitedListener
{
    private $em;

    private $connection;

    private $security;

    private $repository;

    public function __construct(EntityManagerInterface $em, Security $security, Connection $connection, WhoshasvisitedRepository $repository)
    {
        $this->em = $em;
        $this->security = $security;
        $this->connection = $connection;
        $this->repository = $repository;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {

        if ($this->security->getUser() !== null) {

            $time_max = time() - (60 * (60*24));
            $vide = $this->repository->findAll();

            if($vide != NULL) {
                $this->connection->exec('DELETE FROM whoshasvisited WHERE visited_time < '.$time_max);
            }
            $usertest = $this->security->getUser();
            $repo = $this->repository->findOneBySomeField($usertest);
            if($repo == null) {

                $test = new Whoshasvisited();
                $test->setVisiteur($usertest);
                $test->setVisitedTime(time());
                $this->em->persist($test);
            }

            $usertest->setDateVisite(new \DateTime());
            $this->em->flush();
        }

    }
}
