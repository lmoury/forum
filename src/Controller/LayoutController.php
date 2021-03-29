<?php

namespace App\Controller;

use App\Repository\NoticeRepository;
use App\Repository\UserBannirRepository;
use App\Repository\UserRoleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
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
     * @param ObjectManager $this->em
     * @param NoticeRepository $repository
     * @param UserBannirRepository $repoBannir
     * @param UserRoleRepository $repoRole
     */
    public function notice(NoticeRepository $repository, UserRoleRepository $repoRole, UserBannirRepository $repoBannir)
    {
        if ($this->getUser() != null) {
            $this->getUser()->setDateVisite(new \DateTime());
            $this->em->flush();
            if($this->getUser()->getRole()->getId() == 5 and $this->getUser()->getUserBannir()->getFin()) {
                $date= new \DateTime();
                if($this->getUser()->getUserBannir()->getFin() < $date) {
                    $debannir = $repoBannir->getUserBanni($this->getUser());
                    $this->em->remove($debannir);
                    $this->getUser()->setRole($repoRole->find(1));
                    $this->em->flush();
                }
            }
        }
        $notices = $repository->findAll();
        return $this->render('inc/notice.html.twig', [
            'notices' => $notices
        ]);
    }

    /**
     * @Route("/cookie/accept", name="cookie.accept")
     */
    public function cookieAccept(Request $request)
    {
        setcookie('accept_cookie', true, time() + 3600, '/', null, false, true);
        if($_SERVER['HTTP_REFERER']) {
            return $this->redirect($_SERVER['HTTP_REFERER']);
        } else {
            return $this->redirectToRoute('forums');
        }
    }
}
