<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ForumDiscussionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;

class PagesController extends AbstractController
{

    private $current_url = 'home';

    /**
     * @var ObjectManager
     */
    private $em;


    public function __construct(ObjectManager $em)
    {
        $this->em = $em;
    }


    /**
     * @Route("/", name="/")
     * @param ForumDiscussionRepository $repository
     */
    public function index(ForumDiscussionRepository $repository)
    {
        $lastDiscussions = $repository->getLastDiscussionsHome();
        //return $this->redirectToRoute('forums');
        return $this->render('pages/home.html.twig', [
            'current_url' => $this->current_url,
            'lastDiscussions' => $lastDiscussions,
        ]);
    }


    /**
     * @Route("/rules", name="rules")
     */
    public function rules()
    {
        return $this->render('pages/rules.html.twig');
    }


    /**
     * @Route("/cookies", name="cookies")
     */
    public function cookies()
    {
        return $this->render('pages/cookies.html.twig');
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request)
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            if($this->getUser()) {
                $contact->setNom($this->getUser()->getUsername());
                $contact->setEmail($this->getUser()->getEmail());
            }
            $this->em->persist($contact);
            $this->em->flush();
            $this->addFlash('success', 'Votre message à été envoyé, nous vous répondrons le plus vite possible.');
            return $this->redirectToRoute('/');
        }

        return $this->render('pages/contact.html.twig', [
            'form' => $form->createView()
        ]);
    }


}
