<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PagesController extends AbstractController
{

    private $current_url = 'home';

    /**
     * @Route("/", name="/")
     */
    public function index()
    {
        //return $this->redirectToRoute('forums');
        return $this->render('pages/home.html.twig', [
            'current_url' => $this->current_url,
        ]);
    }

}
