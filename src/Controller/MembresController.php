<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserBannir;
use App\Repository\UserRepository;
use App\Repository\UserRoleRepository;
use App\Repository\UserBannirRepository;
use App\Repository\ForumDiscussionRepository;
use App\Repository\ForumCommentaireRepository;
use App\Form\EditMembreType;
use App\Form\EditPasswordMembreType;
use App\Form\EditBannirMembreType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Security("is_granted('ROLE_USER')")
 */
class MembresController extends AbstractController
{

    private $current_url = 'membres';

    /**
     * @var ObjectManager
     */
    private $em;

    /**
     * @var UserRepository
     */
    private $repository;


    public function __construct(ObjectManager $em, UserRepository $repository, PaginatorInterface $paginator)
    {
        $this->em = $em;
        $this->repository = $repository;
        $this->paginator = $paginator;
    }


    /**
     * @Route("/membres", name="membres")
     * @param UserRepository $this->repository
     */
    public function index()
    {
        $users = $this->repository->getListUser();
        return $this->render('membres/index.html.twig', [
            'current_url' => $this->current_url,
            'users' => $users
        ]);
    }


    /**
     * @Route("/membres/{slug}.{id}", name="membres.profil", requirements={"slug": "[a-zA-Z0-9\-\.]*"})
     * @param UserRepository $this->repository
     * @param User $user
     * @param string $slug
     * @param PaginatorInterface paginator
     * @param Request $request
     */
    public function profil(Request $request, User $user, string $slug, ForumDiscussionRepository $repository, ForumCommentaireRepository $repositoryCom )
    {
        if($user->getSlug() !== $slug) {
            return $this->redirectToRoute('membres.profil', ['id' => $user->getId(), 'slug' => $user->getSlug()], 301);
        }
        $user = $this->repository->getUser($user->getId());
        $lastDiscussions = $this->paginator->paginate(
            $repository->getLastDiscussionsUser($user->getId()),
            $request->query->getInt('page', 1),
            20
        );
        $lastCommentaires = $this->paginator->paginate(
            $repositoryCom->getLastCommentairesUser($user->getId()),
            $request->query->getInt('page', 1),
            20
        );

        return $this->render('membres/profil.html.twig', [
            'current_url' => $this->current_url,
            'user' => $user,
            'lastDiscussions' => $lastDiscussions,
            'lastCommentaires' => $lastCommentaires,
        ]);
    }


    /**
     * @Route("/membres/compte", name="membres.compte")
     * @param UserRepository $this->repository
     * @param ObjectManager em
     * @param UserPasswordEncoderInterface $encoder
     * @param Request $request
     */
    public function compte(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = $this->getUser();
        $form = $this->createForm(EditMembreType::class, $user);
        $form->handleRequest($request);

        $formPass = $this->createForm(EditPasswordMembreType::class, $user);
        $formPass->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            $this->addFlash('success', 'Votre compte a bien été modifié');
            return $this->redirectToRoute('membres.compte');
        }
        elseif($formPass->isSubmitted() && $formPass->isValid()) {
            $user->setPassword($encoder->encodePassword($user, $request->request->get('edit_password_membre')['password']['first']));
            $this->em->flush();
            $this->addFlash('success', 'Vos mots de passe on bien été modifié');
            return $this->redirectToRoute('membres.compte');
        }

        return $this->render('membres/compte.html.twig', [
            'current_url' => $this->current_url,
            'user' => $user,
            'form' => $form->createView(),
            'formPass' => $formPass->createView(),
        ]);
    }


    /**
     * @Route("/membres/banni/{id}", name="membres.bannir")
     * @Security("is_granted('ROLE_MODERATEUR')")
     * @param ObjectManager $this->em
     * @param Request $request
     * @param UserRoleRepository $repoRole
     * @param UserBannirRepository $repoBannir
     * @param User $user
     */
    public function bannir(User $user, Request $request, UserRoleRepository $repoRole, UserBannirRepository $repoBannir)
    {
        if($user->getRole()->getId() != 5) {
            $bannir = new UserBannir();
            $form = $this->createForm(EditBannirMembreType::class, $bannir, [
                'action' => $this->generateUrl('membres.bannir', ['id' => $user->getId()]),
            ]);
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()) {
                $user->setRole($repoRole->find(5));
                $bannir->setBanni($user);
                $this->em->persist($bannir);
                $this->em->flush();
                $this->addFlash('success', 'Vous avez banni '.$user->getUsername());
                return $this->redirectToRoute('membres.profil', ['id' => $user->getId(), 'slug' => $user->getSlug()]);
            }
            return $this->render('membres/_formBannir.html.twig', [
                'form' => $form->createView(),
                'membre' => $user
            ]);
        }
        else {
            $debannir = $repoBannir->getUserBanni($user);
            $this->em->remove($debannir);
            $user->setRole($repoRole->find(1));
            $this->addFlash('success', 'Vous avez débanni '.$user->getUsername());
            $this->em->flush();
        }
        return $this->redirectToRoute('membres.profil', ['id' => $user->getId(), 'slug' => $user->getSlug()]);
    }
}
