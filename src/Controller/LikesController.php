<?php

namespace App\Controller;

use App\Entity\Likes;
use App\Entity\ForumDiscussion;
use App\Entity\ForumCommentaire;
use App\Repository\ForumDiscussionRepository;
use App\Repository\ForumCommentaireRepository;
use App\Repository\LikeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class LikesController extends AbstractController
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    /**
     * @Route("/like/discussion.{id}", name="like.discussion")
     * @param EntityManagerInterface em
     * @param ForumDiscussion $discussion
     * @param Request $request
     */
    public function likeDiscussion(ForumDiscussion $discussion, Request $request)
    {
        $like = new Likes();
        $like->setUser($this->getUser());
        $like->setAuteur($discussion->getAuteur());
        $like->setDiscussion($discussion);
        $this->em->persist($like);
        $this->em->flush();
        return $this->redirectToRoute('forum.discussion', ['id' => $discussion->getId(), 'slug' => $discussion->getSlug()]);
    }

    /**
     * @Route("/like/delet/discussion.{id}", name="delet.like.discussion")
     * @param EntityManagerInterface em
     * @param ForumDiscussion $discussion
     * @param LikeRepository $repo
     */
    public function DeletLikeDiscussion(ForumDiscussion $discussion, LikeRepository $repo)
    {
        $likes = $repo->getLikesDiscussion($discussion, $this->getUser());
        $this->em->remove($likes);
        $this->em->flush();
        return $this->redirectToRoute('forum.discussion', ['id' => $discussion->getId(), 'slug' => $discussion->getSlug()]);
    }


    /**
     * @Route("/like/commentaire.{id}", name="like.commentaire")
     * @param EntityManagerInterface em
     * @param ForumCommentaire $commentaire
     * @param Request $request
     */
    public function likeCommentaire(ForumCommentaire $commentaire, Request $request)
    {
        $like = new Likes();
        $like->setUser($this->getUser());
        $like->setAuteur($commentaire->getAuteur());
        $like->setCommentaire($commentaire);
        $this->em->persist($like);
        $this->em->flush();
        return $this->redirectToRoute('forum.discussion', ['id' => $commentaire->getDiscussion()->getId(), 'slug' => $commentaire->getDiscussion()->getSlug()]);
    }


    /**
     * @Route("/like/delet/commentaire.{id}", name="delet.like.commentaire")
     * @param EntityManagerInterface em
     * @param ForumCommentaire $commentaire
     * @param LikeRepository $repo
     */
    public function DeletLikeCommentaire(ForumCommentaire $commentaire, LikeRepository $repo)
    {
        $likes = $repo->getLikesCom($commentaire, $this->getUser());
        $this->em->remove($likes);
        $this->em->flush();
        return $this->redirectToRoute('forum.discussion', ['id' => $commentaire->getDiscussion()->getId(), 'slug' => $commentaire->getDiscussion()->getSlug()]);
    }
}
