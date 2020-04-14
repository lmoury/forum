<?php

namespace App\Controller;

use App\Entity\Messagerie;
use App\Repository\MessagerieRepository;
use App\Form\MessagerieType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;

class MessagerieController extends AbstractController
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
     * @param MessagerieRepository $repository
     */
    public function index(MessagerieRepository $repository)
    {
        //$categories = $repository->finbByLabel($this->getUser());
        return $this->render('messagerie/index.html.twig', [

        ]);
    }

    /**
     * @Route("/conversations/{slug}.{id}", name="conversations.message", requirements={"slug": "[a-zA-Z0-9\-\.]*"})
     * @param MessagerieRepository $repository
     * @param Messagerie $messagerie
     * @param string $slug
     */
    public function conversation(MessagerieRepository $repository, Messagerie $messagerie, string $slug)
    {
        $messagerie->setLu(true);
        $this->em->flush();

        if($messagerie->getSlug() !== $slug) {
          return $this->redirectToRoute('conversations', ['id' => $messagerie->getId(), 'slug' => $messagerie->getSlug()], 301);
        }

        //$conversation = $repository->getConversation();
        return $this->render('messagerie/conversation.html.twig', [
            'conversation' => $messagerie,
        ]);
    }


    /**
     * @Route("/conversations/new", name="conversations.new")
     * @param ObjectManager em
     * @param Request $request
     * @param MessagerieType $form
     * @param Messagerie $messagerie
     */
     public function new(Request $request)
     {

         $messagerie = new Messagerie();
         $form = $this->createForm(MessagerieType::class, $messagerie);
         $form->handleRequest($request);

         if($form->isSubmitted() && $form->isValid() && $request->request->get('tMessage') != null) {
             $messagerie->setExpediteur($this->getUser());
             $messagerie->setMessage($request->request->get('tMessage'));
             $this->em->persist($messagerie);
             $this->em->flush();
             return $this->redirectToRoute('conversations');
         }

         return $this->render('messagerie/new.html.twig', [
             'form' => $form->createView()
         ]);
     }

}
