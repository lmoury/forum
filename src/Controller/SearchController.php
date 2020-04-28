<?php

namespace App\Controller;

use App\Entity\ForumDiscussionSearch;
use App\Repository\ForumDiscussionRepository;
use App\Form\ForumDiscussionSearchType;
use App\Form\ForumDiscussionSearchNavbarType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


/**
 * @Security("has_role('ROLE_USER')")
 */
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
                10
            );

            return $this->render('search/index.html.twig', [
                'current_url' => $this->current_url,
                'discussions' => $discussions,
                'recherche' => $search->getMotCle()
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
}
