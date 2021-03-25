<?php

namespace App\Controller;

use App\Entity\Signalement;
use App\Entity\SignalementRaison;
use App\Entity\ForumDiscussion;
use App\Entity\ForumCommentaire;
use App\Entity\Chatbox;
use App\Repository\SignalementRepository;
use App\Repository\SignalementRaisonRepository;
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

    /**
     * @var ObjectManager
     */
    private $em;


    public function __construct(ObjectManager $em)
    {
        $this->em = $em;
    }


    /**
     * @Route("/signalement", name="signalement")
     * @param SignalementRepository $repository
     * @Security("is_granted('ROLE_MODERATEUR')")
     */
     public function index(SignalementRepository $repository)
     {
         $signalements = $repository->findAll();
         return $this->render('signalement/index.html.twig', [
             'signalements' => $signalements,
         ]);
     }

     /**
      * @Route("/signalement/signal.{id}", name="signalement.signal")
      * @param Signalement $signalement
      * @param SignalementRepository $repository
      * @param ObjectManager $this->em
      * @Security("is_granted('ROLE_MODERATEUR')")
      */
      public function signalement(SignalementRaisonRepository $repository, Signalement $signalement)
      {
          $raisons = $repository->getSignalement($signalement->getId());
          $signalement->setLu(true);
          $this->em->flush();
          return $this->render('signalement/signalement.html.twig', [
              'signalement' => $signalement,
              'raisons' => $raisons,
          ]);
      }

      /**
       * @Route("/signalement/resolu.{id}", name="signalement.resolu")
       * @param Signalement $signalement
       * @param ObjectManager $this->em
       * @Security("is_granted('ROLE_MODERATEUR')")
       */
       public function signalementResolu(Signalement $signalement)
       {
           $signalement->setStatut(2);
           $this->em->flush();
           return $this->redirectToRoute('signalement.signal', ['id' => $signalement->getId()]);
       }

    /**
      * @Route("/signalement/discussion.{id}", name="signalement.discussion")
     * @param ObjectManager $this->em
     * @param ForumDiscussion $discussion
     * @param SignalementRepository $repository
     * @param Request $request
     */
    public function discussion(Request $request, ForumDiscussion $discussion, SignalementRepository $repository)
    {

        if('POST' === $request->getMethod() && $request->request->get('signalement')) {
            $signal = $repository->getSignalDiscu($discussion->getId());
            if($signal == "") {
                $signal = new Signalement();
                $signal->setCreatedAt(new \DateTime());
                $signal->setTitre($discussion->getTitre());
                $signal->setMessage($discussion->getMessage());
                $signal->setUser($discussion->getAuteur());
                $signal->setType(1);
                $signal->setDateMessage($discussion->getDateCreation());
                $signal->setIdSignal($discussion->getId());
                $this->em->persist($signal);
            }
            else {
                $signal->setTitre($discussion->getTitre());
                $signal->setMessage($discussion->getMessage());
                $signal->setLu(false);
                $signal->setStatut(1);
            }
            $signalForm = new SignalementRaison();
            $signalForm->setRaison(htmlspecialchars($request->request->get('signalement')));
            $signalForm->setDateSignalement(new \DateTime());
            $signalForm->setSignaleur($this->getUser());
            $signalForm->setSignalement($signal);
            $this->em->persist($signalForm);
            $this->em->flush();
            $this->addFlash('success', 'Merci d’avoir signalé ce contenu');
            return $this->redirectToRoute('forum.discussion', ['id' => $discussion->getId(), 'slug' => $discussion->getSlug()]);
        }
        return $this->redirectToRoute('/');
    }

    /**
      * @Route("/signalement/commentaire.{id}", name="signalement.commentaire")
     * @param ObjectManager $this->em
     * @param ForumCommentaire $commentaire
     * @param SignalementRepository $repository
     * @param Request $request
     */
    public function commentaire(Request $request, ForumCommentaire $commentaire, SignalementRepository $repository)
    {

        if('POST' === $request->getMethod() && $request->request->get('signalement')) {
            $signal = $repository->getSignalCom($commentaire->getId());
            if($signal == "") {
                $signal = new Signalement();
                $signal->setCreatedAt(new \DateTime());
                $signal->setTitre($commentaire->getdiscussion()->getTitre());
                $signal->setMessage($commentaire->getCommentaire());
                $signal->setUser($commentaire->getAuteur());
                $signal->setType(2);
                $signal->setDateMessage($commentaire->getDateCreation());
                $signal->setIdSignal($commentaire->getId());
                $this->em->persist($signal);
            }
            else {
                $signal->setTitre($commentaire->getdiscussion()->getTitre());
                $signal->setMessage($commentaire->getCommentaire());
                $signal->setLu(false);
                $signal->setStatut(1);
            }
            $signalForm = new SignalementRaison();
            $signalForm->setRaison(htmlspecialchars($request->request->get('signalement')));
            $signalForm->setDateSignalement(new \DateTime());
            $signalForm->setSignaleur($this->getUser());
            $signalForm->setSignalement($signal);
            $this->em->persist($signalForm);
            $this->em->flush();
            $this->addFlash('success', 'Merci d’avoir signalé ce contenu');
            return $this->redirectToRoute('forum.discussion', ['id' => $commentaire->getdiscussion()->getId(), 'slug' => $commentaire->getdiscussion()->getSlug()]);
        }
        return $this->redirectToRoute('/');
    }

    /**
     * @Route("/signalement/chatbox.{id}", name="signalement.chatbox")
     * @param ObjectManager $this->em
     * @param Chatbox $chatbox
     * @param SignalementRepository $repository
     * @param Request $request
     */
    public function chatbox(Request $request, Chatbox $chatbox, SignalementRepository $repository)
    {

        if('POST' === $request->getMethod() && $request->request->get('signalement')) {
            $signal = $repository->getSignalChatbox($chatbox->getId());
            if($signal == "") {
                $signal = new Signalement();
                $signal->setCreatedAt(new \DateTime());
                $signal->setTitre($chatbox->getMessage());
                $signal->setMessage($chatbox->getMessage());
                $signal->setUser($chatbox->getUser());
                $signal->setType(3);
                $signal->setDateMessage($chatbox->getPoster());
                $signal->setIdSignal($chatbox->getId());
                $this->em->persist($signal);
            }
            $signalForm = new SignalementRaison();
            $signalForm->setRaison(htmlspecialchars($request->request->get('signalement')));
            $signalForm->setDateSignalement(new \DateTime());
            $signalForm->setSignaleur($this->getUser());
            $signalForm->setSignalement($signal);
            $this->em->persist($signalForm);
            $this->em->flush();
            $this->addFlash('success', 'Merci d’avoir signalé ce contenu');
        }
        return $this->redirectToRoute('chatbox');
    }


    /**
    * @Route("/signalement/delete.{id}", name="signalement.delete", methods="DELETE")
    * @param ObjectManager $this->em
    * @param Signalement $signalement
    * @param Request $request
    * @Security("is_granted('ROLE_MODERATEUR')")
    */
    public function delete(Signalement $signalement, Request $request)
    {
        if($this->isCsrfTokenValid('delete' . $signalement->getId(), $request->get('_token'))) {
            $this->em->remove($signalement);
            $this->em->flush();
            $this->addFlash('success', 'Signalement supprimé');
        }
        return $this->redirectToRoute('signalement');
    }


}
