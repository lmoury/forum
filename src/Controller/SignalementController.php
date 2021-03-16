<?php

namespace App\Controller;

use App\Entity\Signalement;
use App\Entity\ForumDiscussion;
use App\Entity\ForumCommentaire;
use App\Repository\SignalementRepository;
use App\Repository\ForumCommentaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Security("is_granted('ROLE_USER')")
 */
class SignalementController extends AbstractController
{

    private $current_url = 'conversation';

    /**
     * @var ObjectManager
     */
    private $em;


    public function __construct(ObjectManager $em)
    {
        $this->em = $em;
    }


    /**
     * @Route("/signalement/discussion.{id}", name="signalement.discussion")
     * @Route("/signalement/commentaire.{id}", name="signalement.commentaire")
     * @param ObjectManager $this->em
     * @param ForumDiscussion $discussion
     * @param Request $request
     */
    public function signalement(Request $request, ForumDiscussion $discussion, ForumCommentaireRepository $repository)
    {
        $signalForm = new Signalement();
        if('POST' === $request->getMethod() && $request->request->get('signalement')) {
            $signalForm->setRaison(htmlspecialchars($request->request->get('signalement')));
            $signalForm->setDateSignal(new \DateTime());
            $signalForm->setUser($this->getUser());
            if($request->attributes->get('_route') === 'signalement.discussion') {
                $signalForm->setDiscussion($discussion);
            }
            elseif($request->attributes->get('_route') === 'signalement.commentaire') {
                $commentaire = $repository->getCommentaire($discussion->getForumCommentaires()->getOwner()->getId());
                $discussion = $commentaire->getDiscussion();
                $signalForm->setCommentaire($commentaire);
            }
            $this->em->persist($signalForm);
            $this->em->flush();
            $this->addFlash('success', 'Merci d’avoir signalé ce contenu');
            return $this->redirectToRoute('forum.discussion', ['id' => $discussion->getId(), 'slug' => $discussion->getSlug()]);
        }
        return $this->redirectToRoute('/');
    }



}
