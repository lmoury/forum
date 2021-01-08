<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ForumDiscussionRepository;
use Knp\Component\Pager\PaginatorInterface;
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
     * @Route("/", name="/")
     * @param ForumDiscussionRepository $repository
     * @param PaginatorInterface paginator
     * @param Request $request
     */
    public function index(Request $request, ForumDiscussionRepository $repository)
    {
        //$lastDiscussions = $repository->getLastDiscussionsHome();
        $lastDiscussions = $this->paginator->paginate(
            $repository->getLastDiscussionsHome(),
            $request->query->getInt('page', 1),
            20
        );
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
