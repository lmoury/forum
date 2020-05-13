<?php

namespace App\Controller\Admin;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Driver\Connection;

class DashboardController extends AbstractController
{
    /**
     * @Route("/admin/dashboard", name="admin.dashboard")
     * @param UserRepository $repository
     */
    public function index(UserRepository $repository)
    {
        $date= new \DateTime();
        $online = clone $date;
        $online->modify('-5 minute');
        $userStaff = $repository->getlistStaffOnline($online);
        $serverInfo = array (phpversion(), $_SERVER['SERVER_SOFTWARE'], curl_version()['ssl_version'], curl_version()['version']);
        return $this->render('admin/dashboard/index.html.twig', [
            'serverInfo' => $serverInfo,
            'userStaff' => $userStaff
        ]);
    }


    /**
     * @Route("/admin/statistiques", name="admin.statistique")
     * @param UserRepository $repoUser
     * @param Connection $connection
     */
    public function statistiques(Connection $connection, UserRepository $repoUser)
    {
        $date= new \DateTime();
        $online = clone $date;
        $online->modify('-5 minute');
        $hasvisited = clone $date;
        $hasvisited->modify('-24 hour');
        $whosonline = $repoUser->getCountUserOnlineAndVisited($online);
        $whoshasvisited = $repoUser->getCountUserOnlineAndVisited($hasvisited);
        $compteur= $connection->fetchAll('SELECT
            (SELECT COUNT(*) FROM forum_discussion) as countDiscussion,
            (SELECT COUNT(*) FROM forum_commentaire) as countCommentaire,
            (SELECT COUNT(*) FROM user) as countUser,
            (SELECT COUNT(*) FROM forum_discussion WHERE date_creation > CURDATE( )) as countTodayDiscussion,
            (SELECT COUNT(*) FROM forum_commentaire WHERE date_creation > CURDATE( )) as countTodayCommentaire,
            (SELECT COUNT(*) FROM user WHERE sexe = 1) as countUserMale,
            (SELECT COUNT(*) FROM user WHERE sexe = 2) as countUserFemel,
            (SELECT COUNT(*) FROM forum_categorie WHERE parent is not null) as countForumCat,
            (SELECT COUNT(*) FROM forum_categorie WHERE parent is null) as countCat,
            (SELECT COUNT(*) FROM user WHERE date_inscription > CURDATE( )) as countTodayUser
            ');
        return $this->render('admin/dashboard/statistique.html.twig', [
            'compteur' => $compteur,
            'whoshasvisited' => $whoshasvisited,
            'whosonline' => $whosonline,
        ]);
    }


}
