<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\ConversationUser;
use App\Entity\ConversationReponse;
use App\Repository\ConversationRepository;
use App\Repository\ConversationUserRepository;
use App\Repository\ConversationReponseRepository;
use App\Form\ConversationUserType;
use App\Form\ConversationAddStaffType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Security("is_granted('ROLE_USER')")
 */
class ConversationController extends AbstractController
{

    private $current_url = 'conversation';

    /**
     * @var ObjectManager
     */
    private $em;

    /**
     * @var PaginatorInterface
     */
    private $paginator;


    public function __construct(ObjectManager $em, PaginatorInterface $paginator)
    {
        $this->em = $em;
        $this->paginator = $paginator;
    }


    /**
     * @Route("/conversations", name="conversations")
     * @param PaginatorInterface paginator
     * @param ConversationUserRepository $repository
     */
    public function index(ConversationUserRepository $repository, Request $request)
    {
        $conversations = $this->paginator->paginate(
            $repository->getList($this->getUser()),
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('conversation/index.html.twig', [
            'conversations' => $conversations,
            'current_url' => $this->current_url,

        ]);
    }


    /**
     * @Route("/conversation/{slug}.{id}", name="conversation.message", requirements={"slug": "[a-zA-Z0-9\-\.]*"})
     * @Security("is_granted('ACCESS_CONVERSATION', conversation)")
     * @param ObjectManager em
     * @param ConversationUserRepository $repository
     * @param ConversationReponseRepository $repoRep
     * @param Conversation $conversation
     * @param string $slug
     */
    public function conversation(ConversationUserRepository $repository, Request $request, ConversationReponseRepository $repoRep, Conversation $conversation, string $slug)
    {
        if($conversation->getSlug() !== $slug) {
          return $this->redirectToRoute('conversations', ['id' => $conversation->getId(), 'slug' => $conversation->getSlug()], 301);
        }

        $lu = $repository->getConversationUser($conversation, $this->getUser());
        $lu->setLu(true);
        $this->em->flush();

        $reponses = $this->paginator->paginate(
            $repoRep->getConversationReponses($conversation),
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('conversation/conversation.html.twig', [
            'conversation' => $conversation,
            'current_url' => $this->current_url,
            'reponses' => $reponses
        ]);
    }


    /**
     * @Route("/conversation/new", name="conversation.new")
     * @param ObjectManager em
     * @param Request $request
     * @param ConversationUserType $form
     * @param ConversationUser $formulaire
     * @param Conversation $conversation
     */
     public function new(Request $request)
     {
         $formulaire = new ConversationUser();
         $form = $this->createForm(ConversationUserType::class, $formulaire);
         $form->handleRequest($request);

         if($form->isSubmitted() && $form->isValid() && $request->request->get('tMessage') != null) {
             $conversation = new Conversation();
             $conversation->setTitre($formulaire->getConversation()->getTitre());
             $conversation->setMessage($request->request->get('tMessage'));
             $conversation->setExpediteur($this->getUser());
             $this->em->persist($conversation);

             $boucle = new ConversationUser();
             $boucle->setConversation($conversation);
             $boucle->setParticipant($this->getUser());
             $this->em->persist($boucle);

             foreach ($formulaire->getParticipant() as $user) {
                 $boucle = new ConversationUser();
                 $boucle->setConversation($conversation);
                 $boucle->setParticipant($user);
                 $this->em->persist($boucle);
             }
             $this->em->flush();
             return $this->redirectToRoute('conversation.message', ['id' => $conversation->getId(), 'slug' => $conversation->getSlug()]);
         }

         return $this->render('conversation/new.html.twig', [
             'current_url' => $this->current_url,
             'form' => $form->createView()
         ]);
     }


     /**
      * @Route("/conversation/important/{id}", name="conversation.important")
      * @Security("is_granted('ACCESS_CONVERSATION', conversation)")
      * @param ObjectManager em
      * @param ConversationUserRepository $repository
      * @param Conversation $conversation
      */
     public function important(ConversationUserRepository $repository, Conversation $conversation)
     {

         $important = $repository->getConversationUser($conversation, $this->getUser());

         if($important->getImportant()) {
             $important->setImportant(false);
             $this->addFlash('success', 'Conversation en mode normal');
         }
         else {
             $important->setImportant(true);
             $this->addFlash('success', 'Conversation définie comme important');
         }

         $this->em->flush();
         return $this->redirectToRoute('conversation.message', ['id' => $conversation->getId(), 'slug' => $conversation->getSlug()]);
     }


     /**
      * @Route("/conversation/locked/{id}", name="conversation.locked")
      * @Security("is_granted('ACCESS_CONVERSATION', conversation)")
      * @param ObjectManager em
      * @param Conversation $conversation
      */
     public function locked(Conversation $conversation)
     {
         if($conversation->getLocked()) {
             $conversation->setLocked(false);
             $this->addFlash('success', 'Conversation dévérrouiller');
         }
         else {
             $conversation->setLocked(true);
             $this->addFlash('success', 'Conversation vérrouiller');
         }
         $this->em->flush();
         return $this->redirectToRoute('conversation.message', ['id' => $conversation->getId(), 'slug' => $conversation->getSlug()]);
     }


     /**
      * @Route("/conversation/quitter/{id}", name="conversation.quitter")
      * @Security("is_granted('ACCESS_CONVERSATION', conversation)")
      * @param ObjectManager em
      * @param ConversationUserRepository $repository
      * @param Conversation $conversation
      */
     public function quitter(ConversationUserRepository $repository, Conversation $conversation)
     {
         $quitter = $repository->getConversationUser($conversation, $this->getUser());
         $this->em->remove($quitter);
         $this->addFlash('success', 'Vous avez quitté la conversation');
         $this->em->flush();
         return $this->redirectToRoute('conversations');
     }


     /**
      * @Route("/conversation/reponse/{id}", name="conversation.reponse.new")
      * @Security("is_granted('ACCESS_CONVERSATION', conversation)")
      * @param ObjectManager em
      * @param Conversation $conversation
      * @param ConversationUserRepository $repository
      * @param Request $request
      */
     public function reponse(ConversationUserRepository $repository, Conversation $conversation, Request $request)
     {
         if('POST' === $request->getMethod() && $request->request->get('tMessage')) {
             $participant = $repository->getListConversationUser($conversation);
             foreach ($participant as $part) {
                 $part->setLu(false);
                 $this->em->persist($part);
             }

             $reponse = new ConversationReponse();
             $reponse->setConversationRep($conversation);
             $reponse->setAuteur($this->getUser());
             $reponse->setMessage($request->request->get('tMessage'));
             $this->em->persist($reponse);
             $this->em->flush();
             $this->addFlash('success', 'Réponse ajoutée');
         }
         return $this->redirectToRoute('conversation.message', ['id' => $conversation->getId(), 'slug' => $conversation->getSlug()]);
     }


     /**
       * @Route("/conversation/addstaff/{id}", name="conversation.add.staff")
       * @Security("is_granted('ACCESS_CONVERSATION', conversation)")
       * @param ObjectManager $this->em
       * @param Request $request
       * @param Conversation $conversation
       */
      public function addStaffConversation(Request $request, Conversation $conversation)
      {
          $addStaff = new ConversationUser();
          $form = $this->createForm(ConversationAddStaffType::class, $addStaff, [
              'action' => $this->generateUrl('conversation.add.staff', ['id' => $conversation->getId()]),
          ]);
          $form->handleRequest($request);

          if($form->isSubmitted() && $form->isValid()) {
              $addStaff->setConversation($conversation);
              $this->em->persist($addStaff);
              $this->em->flush();
              $this->addFlash('success', 'Vous avez ajouté '.$addStaff->getParticipant()->getUsername().' à la conversation');
              return $this->redirectToRoute('conversation.message', ['id' => $conversation->getId(), 'slug' => $conversation->getSlug()]);
          }

          return $this->render('conversation/_formAddStaff.html.twig', [
              'form' => $form->createView()
          ]);
      }


      /**
      * @Route("/conversation/{id}", name="conversation.delete", methods="DELETE")
      * @Security("is_granted('ACCESS_CONVERSATION', conversation)")
      * @param ObjectManager em
      * @param Conversation $conversation
      * @param Request $request
      */
      public function delete(Conversation $conversation, Request $request)
      {
          if($this->isCsrfTokenValid('delete' . $conversation->getId(), $request->get('_token'))) {
              $this->em->remove($conversation);
              $this->em->flush();
              $this->addFlash('success', 'La conversation a été supprimer avec success');
          }
          return $this->redirectToRoute('conversations');
      }
}
