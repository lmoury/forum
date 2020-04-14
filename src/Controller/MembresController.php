<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\UserRoleRepository;
use App\Form\EditMembreType;
use App\Form\EditPasswordMembreType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


/**
 * @Security("has_role('ROLE_USER')")
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
    private $repoUser;


    public function __construct(ObjectManager $em, UserRepository $repoUser)
    {
        $this->em = $em;
        $this->repoUser = $repoUser;
    }


    /**
     * @Route("/membres", name="membres")
     * @param UserRepository repoUser
     */
    public function index()
    {
        $users = $this->repoUser->getListUser();

        return $this->render('membres/index.html.twig', [
            'current_url' => $this->current_url,
            'users' => $users
        ]);
    }


    /**
     * @Route("/membres/{slug}.{id}", name="membres.profil", requirements={"slug": "[a-zA-Z0-9\-\.]*"})
     * @param UserRepository repoUser
     * @param User $user
     * @param string $slug
     */
    public function profil(User $user, string $slug)
    {
        if($user->getSlug() !== $slug) {
            return $this->redirectToRoute('membres.profil', ['id' => $user->getId(), 'slug' => $user->getSlug()], 301);
        }

        $user = $this->repoUser->getUser($user->getId());

        return $this->render('membres/profil.html.twig', [
            'current_url' => $this->current_url,
            'user' => $user
        ]);
    }


    /**
     * @Route("/membres/compte", name="membres.compte")
     * @param UserRepository repoUser
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
     * @Route("/membres/banni/{slug}.{id}", name="membres.bannir", requirements={"slug": "[a-zA-Z0-9\-\.]*"})
     * @Security("is_granted('ROLE_MODERATEUR')")
     * @param ObjectManager em
     * @param UserRoleRepository $repoRole
     * @param User $user
     * @param string $slug
     */
    public function bannir(User $user, string $slug, UserRoleRepository $repoRole)
    {

        if($user->getRole()->getId() == 5) {
            $user->setRole($repoRole->find(1));
            $this->addFlash('success', 'Utilisateur débanni');
        }
        else {
            $user->setRole($repoRole->find(5));
            $this->addFlash('success', 'Utilisateur banni');
        }
        $this->em->flush();
        return $this->redirectToRoute('membres.profil', ['id' => $user->getId(), 'slug' => $user->getSlug()]);
    }
}
