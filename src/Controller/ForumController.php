<?php

namespace App\Controller;

use App\Entity\ForumCategorie;
use App\Entity\ForumDiscussion;
use App\Entity\ForumCommentaire;
use App\Entity\ForumDiscussionView;
use App\Repository\ForumCategorieRepository;
use App\Repository\ForumDiscussionRepository;
use App\Repository\ForumDiscussionViewRepository;
use App\Repository\ForumCommentaireRepository;
use App\Repository\UserRepository;
use App\Form\DiscussionType;
use App\Form\DeplacerDiscussionType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class ForumController extends AbstractController
{

    private $current_url = 'forums';

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
     * @Route("/forums", name="forums")
     * @param ForumCategorieRepository $repository
     */
    public function index(ForumCategorieRepository $repository)
    {
        $categories = $repository->getListCateg();
        return $this->render('forum/index.html.twig', [
            'current_url' => $this->current_url,
            'categories' => $categories,
        ]);
    }


    /**
     * @Route("/forums/{slug}.{id}", name="forum.discussions", requirements={"slug": "[a-zA-Z0-9\-\.]*"})
     * @Security("is_granted('ACCESS_DISCUSSIONS', categorie)")
     * @param PaginatorInterface paginator
     * @param ForumCategorie $categorie
     * @param ForumDiscussionRepository $repository
     * @param Request $request
     * @param string $slug
     */
     public function discussions(Request $request, ForumDiscussionRepository $repository, ForumCategorie $categorie, string $slug, ForumCategorieRepository $repositoryCateg)
     {
         if($categorie->getSlug() !== $slug) {
             return $this->redirectToRoute('forum.discussions', ['id' => $categorie->getId(), 'slug' => $categorie->getSlug()], 301);
         }

         $discussions = $this->paginator->paginate(
             $repository->getListDiscussions($categorie->getId()),
             $request->query->getInt('page', 1),
             20
         );

         $categories = $repositoryCateg->getListCateg();
         return $this->render('forum/discussions.html.twig', [
             'current_url' => $this->current_url,
             'discussions' => $discussions,
             'categorie' => $categorie,
             'categories' => $categories,
         ]);
     }


     /**
    * @Route("/forums/{slug}.{id}/new", name="forum.discussions.new", requirements={"slug": "[a-zA-Z0-9\-\.]*"})
    * @Security("is_granted('ROLE_USER')", message="You have to be logged in")
    * @param ObjectManager em
    * @param ForumCategorie $categorie
    * @param Request $request
    * @param string $slug
    */
    public function new(Request $request, ForumCategorie $categorie, string $slug)
    {

        if($categorie->getSlug() !== $slug) {
            return $this->redirectToRoute('forum.discussions.new', ['id' => $categorie->getId(), 'slug' => $categorie->getSlug()], 301);
        }

        $discussion = new ForumDiscussion();
        $form = $this->createForm(DiscussionType::class, $discussion);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() && $request->request->get('tMessage') != null) {
            $message = htmlspecialchars($request->request->get('tMessage'));
            $discussion->setCategorie($categorie);
            $discussion->setAuteur($this->getUser());
            $discussion->setMessage($message);
            $this->em->persist($discussion);
            $this->em->flush();
            return $this->redirectToRoute('forum.discussion', ['id' => $discussion->getId(), 'slug' => $discussion->getSlug()]);
        }

        return $this->render('forum/new.html.twig', [
            'current_url' => $this->current_url,
            'form' => $form->createView()
        ]);
    }


    /**
    * @Route("/discussion/{slug}.{id}", name="forum.discussion", requirements={"slug": "[a-zA-Z0-9\-\.]*"})
    * @Route("/discussion/{slug}.{id}/edit/{idCom}", name="forum.commentaire.editer", requirements={"slug": "[a-zA-Z0-9\-\.]*"})
    * @Security("is_granted('ACCESS_DISCUSSION', discussion)")
    * @param PaginatorInterface paginator
    * @param ObjectManager em
    * @param ForumDiscussion $discussion
    * @param ForumCommentaireRepository $comRepo
    * @param ForumDiscussionViewRepository $repoView
    * @param Request $request
    * @param string $slug
    */
    public function discussion(int $idCom = null, Request $request, ForumDiscussion $discussion, ForumCommentaireRepository $comRepo, string $slug, ForumDiscussionViewRepository $repoView): Response
    {
        $discussionView = $repoView->getDiscussionView($this->getUser(), $discussion);
        if(!$discussionView) {
            $view = new ForumDiscussionView();
            $view->setUser($this->getUser());
            $view->setDiscussion($discussion);
            $this->em->persist($view);
            $discussion->setAffichage($discussion->getAffichage()+1);
            $this->em->flush();
        }

        if($discussion->getSlug() !== $slug) {
          return $this->redirectToRoute('forum.discussion', ['id' => $discussion->getId(), 'slug' => $discussion->getSlug()], 301);
        }

        if($idCom != null) {
            $idCom = $comRepo->find($idCom);
        }

        if('POST' === $request->getMethod() && $request->request->get('tMessage')) {
            $idCom->setCommentaire($request->request->get('tMessage'));
            $idCom->setDateEdition(new \DateTime());
            $this->em->flush();
            return $this->redirectToRoute('forum.discussion', ['id' => $discussion->getId(), 'slug' => $discussion->getSlug()]);
        }

        $commentaires =  $this->paginator->paginate(
            $comRepo->getCommentaires($discussion->getId()),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('forum/discussion.html.twig', [
          'current_url' => $this->current_url,
          'discussion' => $discussion,
          'commentaires' => $commentaires,
          'comEdit' => $idCom,
        ]);
    }


    /**
    * @Route("/discussion/{slug}.{id}/editer", name="forum.discussion.editer", requirements={"slug": "[a-zA-Z0-9\-\.]*"}, methods="GET|POST")
    * @Security("is_granted('DELET_EDIT_DISCISSION', discussion) or is_granted('ROLE_MODERATEUR')")
    * @param ObjectManager em
    * @param ForumDiscussion $discussion
    * @param Request $request
    * @param string $slug
    */
    public function editer(ForumDiscussion $discussion, Request $request, string $slug)
    {

        if($discussion->getSlug() !== $slug) {
            return $this->redirectToRoute('forum.discussion.editer', ['id' => $discussion->getId(), 'slug' => $discussion->getSlug()], 301);
        }

        $form = $this->createForm(DiscussionType::class, $discussion);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() && $request->request->get('tMessage') != null) {
            $discussion->setMessage($request->request->get('tMessage'));
            $discussion->setDateEdition(new \DateTime());
            $this->em->flush();
            return $this->redirectToRoute('forum.discussion', ['id' => $discussion->getId(), 'slug' => $discussion->getSlug()]);
        }

        return $this->render('forum/editer.html.twig', [
            'current_url' => $this->current_url,
            'form' => $form->createView(),
            'discussion' => $discussion
        ]);
    }


    /**
    * @Route("/discussion/{id}", name="forum.discussion.delete", methods="DELETE")
    * @Security("is_granted('DELET_EDIT_DISCISSION', discussion) or is_granted('ROLE_MODERATEUR')")
    * @param ObjectManager em
    * @param ForumDiscussion $discussion
    * @param Request $request
    * @return Symfony\Component\HttpFoundation\Response
    */
    public function delete(ForumDiscussion $discussion, Request $request)
    {
        if($this->isCsrfTokenValid('delete' . $discussion->getId(), $request->get('_token'))) {
            $this->em->remove($discussion);
            $this->em->flush();
            $this->addFlash('success', 'La discussion a été supprimer avec success');
        }
        return $this->redirectToRoute('forum.discussions', ['id' => $discussion->getCategorie()->getId(), 'slug' =>  $discussion->getCategorie()->getSlug()]);
    }


    /**
   * @Route("/commentaire/new-{id}", name="forum.commentaire.new")
   * @Security("is_granted('ROLE_USER')")
   * @param ObjectManager em
   * @param ForumDiscussion $discussion
   * @param Request $request
   */
   public function commentaireNew(ForumDiscussion $discussion, Request $request)
   {

       $commentaire = new ForumCommentaire();

       if('POST' === $request->getMethod() && $request->request->get('tMessage')) {
           $message = htmlspecialchars($request->request->get('tMessage'));
           $discussion->setDateNewCom(new \DateTime());
           $commentaire->setDiscussion($discussion);
           $commentaire->setAuteur($this->getUser());
           $commentaire->setCommentaire($message);
           $this->em->persist($commentaire);
           $this->em->persist($discussion);
           $this->em->flush();
       }
       return $this->redirectToRoute('forum.discussion', ['id' => $discussion->getId(), 'slug' =>  $discussion->getSlug()]);
   }


   /**
   * @Route("/commmentaire/{id}", name="forum.commentaire.delete", methods="DELETE")
   * @Security("is_granted('DELET_EDIT_COMMENTAIRE', commentaire) or is_granted('ROLE_MODERATEUR')")
   * @param ObjectManager em
   * @param ForumCommentaire $commentaire
   * @param Request $request
   */
   public function commentaireDelete(ForumCommentaire $commentaire, Request $request)
   {
       $this->em->remove($commentaire);
       $this->em->flush();
       $this->addFlash('success', 'Commentaire supprimer avec success');
       return $this->redirectToRoute('forum.discussion', ['id' => $commentaire->getDiscussion()->getId(), 'slug' =>  $commentaire->getDiscussion()->getSlug()]);
   }


   /**
    * @Route("/forums/locked/{id}", name="forum.locked")
    * @Security("is_granted('DELET_EDIT_DISCISSION', discussion) or is_granted('ROLE_MODERATEUR')")
    * @param ObjectManager em
    * @param ForumDiscussion $discussion
    */
   public function locked(ForumDiscussion $discussion)
   {

       if($discussion->getLocked()) {
           $discussion->setLocked(false);
           $this->addFlash('success', 'Discussion dévérrouiller');
       }
       else {
           $discussion->setLocked(true);
           $this->addFlash('success', 'Discussion vérrouiller');
       }
       $this->em->flush();
       return $this->redirectToRoute('forum.discussions', ['id' => $discussion->getCategorie()->getId(), 'slug' => $discussion->getCategorie()->getSlug()]);
   }

   /**
    * @Route("/forums/important/{id}", name="forum.important")
    * @Security("is_granted('ROLE_MODERATEUR')")
    * @param ObjectManager em
    * @param ForumDiscussion $discussion
    */
   public function important(ForumDiscussion $discussion)
   {

       if($discussion->getImportant()) {
           $discussion->setImportant(false);
           $this->addFlash('success', 'Discussion désépinglé');
       }
       else {
           $discussion->setImportant(true);
           $this->addFlash('success', 'Discussion épinglé');
       }
       $this->em->flush();
       return $this->redirectToRoute('forum.discussions', ['id' => $discussion->getCategorie()->getId(), 'slug' => $discussion->getCategorie()->getSlug()]);
   }


   /**
     * @Route("/forums/deplacer/{id}", name="forum.deplacer.discussion")
     * @Security("is_granted('DELET_EDIT_DISCISSION', discussion) or is_granted('ROLE_MODERATEUR')")
     * @param ObjectManager $this->em
     * @param Request $request
     */
    public function deplacerDiscussion(Request $request, ForumDiscussion $discussion)
    {

        $form = $this->createForm(DeplacerDiscussionType::class, $discussion, [
            'action' => $this->generateUrl('forum.deplacer.discussion', ['id' => $discussion->getId()]),
        ]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            $this->addFlash('success', 'La discussion à été déplacé');
            return $this->redirectToRoute('forum.discussions', ['id' => $discussion->getCategorie()->getId(), 'slug' => $discussion->getCategorie()->getSlug()]);
        }

        return $this->render('forum/_formDeplacerDiscussion.html.twig', [
            'form' => $form->createView(),
            'deplacer' => $discussion
        ]);
    }

}
