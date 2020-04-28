<?php

namespace App\Controller;

use App\Repository\NoticeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Persistence\ObjectManager;

class LayoutController extends AbstractController
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
     * @param ObjectManager em
     * @param NoticeRepository $repository
     */
    public function notice(NoticeRepository $repository)
    {
        $notices = $repository->findAll();
        return $this->render('inc/notice.html.twig', [
            'notices' => $notices
        ]);
    }

}
