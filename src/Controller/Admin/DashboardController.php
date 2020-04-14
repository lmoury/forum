<?php

namespace App\Controller\Admin;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Doctrine\DBAL\Driver\Connection;

class DashboardController extends AbstractController
{

    /**
     * @var ObjectManager
     */
    private $em;


    public function __construct(ObjectManager $em)
    {
        $this->em = $em;
    }


    /**
     * @Route("/admin/dashboard", name="admin.dashboard")
     */
    public function index(Connection $connection)
    {

        return $this->render('admin/dashboard/index.html.twig', [

        ]);
    }


    /**
     * @Route("/admin/statistiques", name="admin.statistique")
     */
    public function statistiques(Connection $connection)
    {
        $compteur= $connection->fetchAll('SELECT
            (SELECT COUNT(*) FROM forum_discussion) as countDiscussion,
            (SELECT COUNT(*) FROM forum_commentaire) as countCommentaire,
            (SELECT COUNT(*) FROM user) as countUser,
            (SELECT COUNT(*) FROM forum_discussion WHERE date_creation > CURDATE( )) as countTodayDiscussion,
            (SELECT COUNT(*) FROM forum_commentaire WHERE date_creation > CURDATE( )) as countTodayCommentaire,
            (SELECT COUNT(*) FROM whosonline) as countWhosonline,
            (SELECT COUNT(*) FROM whoshasvisited) as countWhoshasvisited,
            (SELECT COUNT(*) FROM user WHERE sexe = 1) as countUserMale,
            (SELECT COUNT(*) FROM user WHERE sexe = 2) as countUserFemel,
            (SELECT COUNT(*) FROM forum_categorie WHERE parent is not null) as countForumCat,
            (SELECT COUNT(*) FROM forum_categorie WHERE parent is null) as countCat,
            (SELECT COUNT(*) FROM user WHERE date_inscription > CURDATE( )) as countTodayUser
            ');
        return $this->render('admin/dashboard/statistique.html.twig', [
            'compteur' => $compteur
        ]);
    }


}
