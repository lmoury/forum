<?php

namespace App\Controller\Admin;

use App\Entity\ForumCategorie;
use App\Repository\ForumCategorieRepository;
use App\Form\Admin\ForumCategorieType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class ForumController extends AbstractController
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
     * @Route("/admin/categories", name="admin.categories")
     * @param ForumCategorieRepository $repository
     */
    public function index(ForumCategorieRepository $repository)
    {
        $categories = $repository->getListCateg();
        return $this->render('admin/forum/categorie/index.html.twig', [
            'categories' => $categories,
        ]);
    }


    /**
     * @Route("/admin/categorie/new", name="admin.categorie.new")
     * @param EntityManagerInterface em
     * @param Request $request
     * @param ForumCategorieRepository $repository
     * @param new ForumCategorie()
     * @param ForumCategorieType
     */
    public function new(Request $request, ForumCategorieRepository $repository)
    {
        $categorie = new ForumCategorie();
        $categories = $repository->getCategorieParent();
        $form = $this->createForm(ForumCategorieType::class, $categorie, [
            'categories' => $categories,
        ]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($categorie);
            $this->em->flush();
            return $this->redirectToRoute('admin.categories');
        }

        return $this->render('admin/forum/categorie/new.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
    * @Route("/admin/categorie/{slug}.{id}/editer", name="admin.categorie.editer", requirements={"slug": "[a-zA-Z0-9\-\.]*"}, methods="GET|POST")
    * @param EntityManagerInterface em
    * @param ForumCategorie $categorie
    * @param ForumCategorieRepository $repository
    * @param ForumCategorieType
    * @param Request $request
    * @param string $slug
    */
    public function editer(ForumCategorie $categorie, Request $request, string $slug, ForumCategorieRepository $repository)
    {

        if($categorie->getSlug() !== $slug) {
            return $this->redirectToRoute('admin.categorie.editer', ['id' => $categorie->getId(), 'slug' => $categorie->getSlug()], 301);
        }

        $categories = $repository->getCategorieParent();
        $form = $this->createForm(ForumCategorieType::class, $categorie, [
            'categories' => $categories,
        ]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            return $this->redirectToRoute('admin.categories');
        }

        return $this->render('admin/forum/categorie/editer.html.twig', [
            'form' => $form->createView(),
            'categorie' => $categorie
        ]);
    }


    /**
    * @Route("/admin/categorie/{id}", name="admin.categorie.delete", methods="DELETE")
    * @param EntityManagerInterface em
    * @param ForumCategorie $categorie
    * @param Request $request
    */
    public function delete(ForumCategorie $categorie, Request $request)
    {
        if($this->isCsrfTokenValid('delete' . $categorie->getId(), $request->get('_token'))) {
            $this->em->remove($categorie);
            $this->em->flush();
            $this->addFlash('success', 'La catégories à été supprimer avec success');
        }
        return $this->redirectToRoute('admin.categories');
    }
}
