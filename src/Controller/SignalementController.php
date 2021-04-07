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
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Security("is_granted('ROLE_USER')")
 */
class SignalementController extends AbstractController
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var PaginatorInterface
     */
    private $paginator;


    public function __construct(EntityManagerInterface $em, PaginatorInterface $paginator)
    {
        $this->em = $em;
        $this->paginator = $paginator;
    }


    /**
     * @Route("/signalement", name="signalement")
     * @param SignalementRepository $repository
     * @param Request $request
     * @Security("is_granted('ROLE_MODERATEUR')")
     */
     public function index(SignalementRepository $repository, Request $request)
     {
         $signalements = $this->paginator->paginate(
             $repository->getSignalement(),
             $request->query->getInt('page', 1),
             20
         );
         return $this->render('signalement/index.html.twig', [
             'signalements' => $signalements,
         ]);
     }

     /**
      * @Route("/signalement/signal.{id}", name="signalement.signal")
      * @param Signalement $signalement
      * @param SignalementRepository $repository
      * @param EntityManagerInterface $this->em
      * @param Request $request
      * @Security("is_granted('ROLE_MODERATEUR')")
      */
      public function signalement(SignalementRaisonRepository $repository, Signalement $signalement, Request $request)
      {
          $raisons = $this->paginator->paginate(
              $repository->getSignalementRaison($signalement->getId()),
              $request->query->getInt('page', 1),
              10
          );
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
       * @param EntityManagerInterface $this->em
       * @Security("is_granted('ROLE_MODERATEUR')")
       */
       public function signalementResolu(Signalement $signalement)
       {
           $signalement->setStatut(2);
           $signalForm = new SignalementRaison();
           $signalForm->setRaison('Signalement mis en résolu');
           $signalForm->setDateSignalement(new \DateTime());
           $signalForm->setSignaleur($this->getUser());
           $signalForm->setSignalement($signalement);
           $this->em->persist($signalForm);
           $this->em->flush();
           return $this->redirectToRoute('signalement.signal', ['id' => $signalement->getId()]);
       }

    /**
      * @Route("/signalement/discussion.{id}", name="signalement.discussion")
     * @param EntityManagerInterface $this->em
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
                $signal->setUser($discussion->getAuteur());
                $signal->setType(1);
                $signal->setDateMessage($discussion->getDateCreation());
                $signal->setIdSignal($discussion->getId());
            }
            $signal->setTitre($discussion->getTitre());
            $signal->setMessage($discussion->getMessage());
            $signal->setLu(false);
            $signal->setStatut(1);
            $signal->setDateNewRaison(new \DateTime());
            $this->em->persist($signal);

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
     * @param EntityManagerInterface $this->em
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
                $signal->setUser($commentaire->getAuteur());
                $signal->setType(2);
                $signal->setDateMessage($commentaire->getDateCreation());
                $signal->setIdSignal($commentaire->getId());
            }
            $signal->setTitre($commentaire->getdiscussion()->getTitre());
            $signal->setMessage($commentaire->getCommentaire());
            $signal->setLu(false);
            $signal->setStatut(1);
            $signal->setDateNewRaison(new \DateTime());
            $this->em->persist($signal);

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
     * @param EntityManagerInterface $this->em
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
            }
            $signal->setMessage($chatbox->getMessage());
            $signal->setLu(false);
            $signal->setStatut(1);
            $signal->setDateNewRaison(new \DateTime());
            $this->em->persist($signal);

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
    * @param EntityManagerInterface $this->em
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


    /**
     * @param SignalementRepository $repository
     */
    public function alerteSignal(SignalementRepository $repository)
    {
        $signalAlerte = $repository->findAll();
        return $this->render('signalement/navbar_signalement.html.twig', [
            'signalAlerte' => $signalAlerte,
        ]);
    }

}
