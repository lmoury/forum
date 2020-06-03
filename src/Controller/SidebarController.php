<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\UserRoleRepository;
use App\Repository\ForumDiscussionRepository;
use App\Repository\ForumCommentaireRepository;
use App\Repository\SocialRepository;
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


    /**
     * @param UserRoleRepository $repoRole
     * @param UserRepository $repoUser
     * @param Connection $connection
     */
    public function menu(UserRepository $repoUser, UserRoleRepository $repoRole)
    {
        $date= new \DateTime();
        $online = clone $date;
        $online->modify('-5 minute');
        $hasvisited = clone $date;
        $hasvisited->modify('-24 hour');
        $whosonline = $repoUser->getlistUserOnlineAndVisited($online);
        $whoshasvisited = $repoUser->getlistUserOnlineAndVisited($hasvisited);
        $roles = $repoRole->getRoles();
        return $this->render('inc/sidebar/sidebar_menu.html.twig', [
            'roles' => $roles,
            'whoshasvisited' => $whoshasvisited,
            'whosonline' => $whosonline
        ]);
    }

    /**
     * @param SocialRepository $repoSocial
     */
    public function social(SocialRepository $repoSocial)
    {
        $sociaux = $repoSocial->findAll();
        return $this->render('inc/sidebar/sidebar_social.html.twig', [
            'sociaux' => $sociaux
        ]);
    }


    /**
     * @param UserRepository $repository
     */
    public function staffOnline(UserRepository $repository)
    {
        $date= new \DateTime();
        $online = clone $date;
        $online->modify('-5 minute');
        $userStaff = $repository->getlistStaffOnline($online);
        return $this->render('inc/sidebar/sidebar_staff.html.twig', [
            'userStaff' => $userStaff
        ]);
    }

}
