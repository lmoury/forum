<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\ConversationUser;
use App\Repository\ConversationRepository;
use App\Repository\ConversationUserRepository;
use App\Form\ConversationUserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Security("has_role('ROLE_USER')")
 */
class ConversationController extends AbstractController
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
     * @Route("/conversations", name="conversations")
     * @param ConversationRepository $repository
     */
    public function index(ConversationRepository $repository)
    {
        $conversations = $repository->getListConversations($this->getUser());
        return $this->render('conversation/index.html.twig', [
            'conversations' => $conversations
        ]);
    }


    /**
     * @Route("/conversation/{slug}.{id}", name="conversation.message", requirements={"slug": "[a-zA-Z0-9\-\.]*"})
     * @param ObjectManager em
     * @param ConversationUserRepository $repository
     * @param Conversation $conversation
     * @param string $slug
     */
    public function conversation(ConversationUserRepository $repository, Conversation $conversation, string $slug)
    {
        if($this->getUser() != $conversation->getExpediteur()) {
            $lu = $repository->getConversationUser($conversation, $this->getUser());
            $lu->setLu(true);
            $this->em->flush();
        }

        if($conversation->getSlug() !== $slug) {
          return $this->redirectToRoute('conversations', ['id' => $conversation->getId(), 'slug' => $conversation->getSlug()], 301);
        }

        //$conversation = $repository->getConversation();
        return $this->render('conversation/conversation.html.twig', [
            'conversation' => $conversation,
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
             foreach ($formulaire->getParticipant() as $user) {
                 $boucle = new ConversationUser();
                 $boucle->setConversation($conversation);
                 $boucle->setParticipant($user);
                 $this->em->persist($boucle);
             }
             $this->em->flush();
             return $this->redirectToRoute('conversations');
         }

         return $this->render('conversation/new.html.twig', [
             'form' => $form->createView()
         ]);
     }


     /**
      * @Route("/conversation/important/{id}", name="conversation.important")
      * @param ObjectManager em
      * @param ConversationUserRepository $repository
      * @param Conversation $conversation
      */
     public function important(ConversationUserRepository $repository, Conversation $conversation)
     {

         if($this->getUser() != $conversation->getExpediteur()) {
             $important = $repository->getConversationUser($conversation, $this->getUser());

             if($important->getImportant()) {
                 $important->setImportant(false);
                 $this->addFlash('success', 'Conversation en mode normal');
             }
             else {
                 $important->setImportant(true);
                 $this->addFlash('success', 'Conversation définie comme important');
             }
         }

         $this->em->flush();
         return $this->redirectToRoute('conversation.message', ['id' => $conversation->getId(), 'slug' => $conversation->getSlug()]);
     }


     /**
      * @Route("/conversation/locked/{id}", name="conversation.locked")
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
}
