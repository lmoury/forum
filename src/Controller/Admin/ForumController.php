<?php

namespace App\Controller\Admin;

use App\Repository\ForumCategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ForumController extends AbstractController
{

    private $current_url = 'forums';

    /**
     * @Route("/admin/forums", name="admin.forums")
     * @param ForumCategorieRepository $repository
     */
    public function index(ForumCategorieRepository $repository)
    {
        $categories = $repository->getListCateg();
        return $this->render('admin/forum/index.html.twig', [
            'current_url' => $this->current_url,
            'categories' => $categories,
        ]);
    }

}
