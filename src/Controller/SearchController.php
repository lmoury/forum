<?php

namespace App\Controller;

use App\Entity\ForumDiscussionSearch;
use App\Entity\Tag;
use App\Repository\ForumDiscussionRepository;
use App\Form\ForumDiscussionSearchType;
use App\Form\ForumDiscussionSearchNavbarType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class SearchController extends AbstractController
{

    private $current_url = 'search';

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
     * @Route("/search", name="search")
     * @Security("is_granted('ROLE_USER')")
     * @param ObjectManager em
     * @param Request $request
     * @param ForumDiscussionRepository $repository
     */
    public function search(ForumDiscussionRepository $repository, Request $request)
    {

        $search = new ForumDiscussionSearch();
        $form = $this->createForm(ForumDiscussionSearchType::class, $search);
        $form->handleRequest($request);

        if($search->getMotCle()) {
            $discussions =  $this->paginator->paginate(
                $repository->getSearchDiscussion($search),
                $request->query->getInt('page', 1),
                20
            );

            return $this->render('search/index.html.twig', [
                'current_url' => $this->current_url,
                'discussions' => $discussions,
                'recherche' => 'Résultats de la recherche: '.$search->getMotCle()
            ]);
        }
        else {
            return $this->render('search/_form.html.twig', [
                'current_url' => $this->current_url,
                'form' => $form->createView(),
            ]);
        }

    }


    /**
     * @param ObjectManager em
     * @param Request $request
     * @param ForumDiscussionRepository $repository
     */
    public function searchNavbar(ForumDiscussionRepository $repository, Request $request)
    {

        $search = new ForumDiscussionSearch();
        $form = $this->createForm(ForumDiscussionSearchNavbarType::class, $search, [
            'action' => $this->generateUrl('search'),
        ]);

        return $this->render('inc\navbar\_form-search.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/tags/{slug}.{id}", name="tags", requirements={"slug": "[a-zA-Z0-9\-\.]*"})
     * @Security("is_granted('ROLE_USER')")
     * @param ObjectManager em
     * @param Request $request
     * @param Tag $tag
     * @param ForumDiscussionRepository $repository
     * @param string $slug
     */
    public function tags(ForumDiscussionRepository $repository, Request $request, Tag $tag, string $slug)
    {
        if($tag->getSlug() !== $slug) {
            return $this->redirectToRoute('tags', ['id' => $tag->getId(), 'slug' => $tag->getSlug()], 301);
        }

        $discussions =  $this->paginator->paginate(
            $repository->getListDiscussionsTag($tag->getId()),
            $request->query->getInt('page', 1),
            20
        );

        return $this->render('search/index.html.twig', [
            'discussions' => $discussions,
            'recherche' => 'Résultats pour le tag: '.$tag->getNom()
        ]);
    }
}
