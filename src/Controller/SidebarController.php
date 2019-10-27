<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\ForumDiscussionRepository;
use App\Repository\ForumCommentaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Driver\Connection;

class SidebarController extends AbstractController
{

    /**
     * @param UserRepository $repository 
     * @param Connection $connection
     */
    public function statistique(Connection $connection,UserRepository $repository)
    {
        $compteur= $connection->fetchAll('SELECT
            (SELECT COUNT(*) FROM forum_discussion) as countDiscussion,
            (SELECT COUNT(*) FROM forum_commentaire) as countCommentaire,
            (SELECT COUNT(*) FROM user) as countUser,
            (SELECT COUNT(*) FROM forum_discussion WHERE date_creation > CURDATE( )) as countTodayDiscussion,
            (SELECT COUNT(*) FROM forum_commentaire WHERE date_creation > CURDATE( )) as countTodayCommentaire,
            (SELECT COUNT(*) FROM user WHERE date_inscription > CURDATE( )) as countTodayUser
            ');
        $lastUser = $repository->getLastUser();
        return $this->render('inc/sidebar/sidebar_statistique.html.twig', [
            'lastUser' => $lastUser,
            'compteur' => $compteur
        ]);
    }


    /**
     * @param ForumDiscussionRepository $repoDiscussion
     * @param ForumCommentaireRepository $repoCommentaires
     */
    public function forum(ForumDiscussionRepository $repoDiscussion, ForumCommentaireRepository $repoCommentaire)
    {
        $lastDiscussion = $repoDiscussion->getLastDiscussions();
        $lastCommentaire = $repoCommentaire->getLastCommentaires();
        return $this->render('inc/sidebar/sidebar_forum.html.twig', [
            'lastDiscussion' => $lastDiscussion,
            'lastCommentaire' => $lastCommentaire,
        ]);
    }

}
