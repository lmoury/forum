<?php

namespace App\Controller\Admin;

use App\Entity\Prefixe;
use App\Repository\PrefixeRepository;
use App\Repository\ForumCategorieRepository;
use App\Form\Admin\PrefixeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class PrefixeController extends AbstractController
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    /**
     * @Route("/admin/prefixes", name="admin.prefixes")
     * @param PrefixeRepository $repository
     */
    public function index(PrefixeRepository $repository)
    {
        $prefixes = $repository->findAll();
        return $this->render('admin/forum/prefixe/index.html.twig', [
            'prefixes' => $prefixes,
        ]);
    }


    /**
     * @Route("/admin/prefixes/new", name="admin.prefixes.new")
     * @param EntityManagerInterface em
     * @param Request $request
     * @param ForumCategorieRepository $repository
     * @param PrefixeType
     */
    public function new(Request $request, ForumCategorieRepository $repository)
    {
        $prefixe = new Prefixe();
        $categories = $repository->getCategorieOptions();
        $form = $this->createForm(PrefixeType::class, $prefixe, [
            'category' => $categories,
        ]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($prefixe);
            $this->em->flush();
            return $this->redirectToRoute('admin.prefixes');
        }

        return $this->render('admin/forum/prefixe/new.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
    * @Route("/admin/prefixes/{id}/editer", name="admin.prefixes.editer", methods="GET|POST")
    * @param EntityManagerInterface em
    * @param ForumCategorieRepository $repository
    * @param Prefixe $prefixe
    * @param Request $request
    * @param PrefixeType
    */
    public function editer(Prefixe $prefixe, Request $request, ForumCategorieRepository $repository)
    {

        $categories = $repository->getCategorieOptions();
        $form = $this->createForm(PrefixeType::class, $prefixe, [
            'category' => $categories,
        ]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            return $this->redirectToRoute('admin.prefixes');
        }

        return $this->render('admin/forum/prefixe/editer.html.twig', [
            'form' => $form->createView(),
            'prefixe' => $prefixe,
        ]);
    }


    /**
    * @Route("/admin/prefixes/delete.{id}", name="admin.prefixes.delete", methods="DELETE")
    * @param EntityManagerInterface $this->em
    * @param Prefixe $prefixe
    * @param Request $request
    */
    public function delete(Prefixe $prefixe, Request $request)
    {
        if($this->isCsrfTokenValid('delete' . $prefixe->getId(), $request->get('_token'))) {
            $this->em->remove($prefixe);
            $this->em->flush();
            $this->addFlash('success', 'Le prefixe à été supprimé');
        }
        return $this->redirectToRoute('admin.prefixes');
    }

}
