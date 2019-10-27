<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    private $current_url = 'home';

    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        return $this->render('admin/dashboard.html.twig', [
            'current_url' => $this->current_url,
        ]);
    }

}
