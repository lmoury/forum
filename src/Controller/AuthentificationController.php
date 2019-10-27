<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\InscriptionType;
use App\Repository\UserRoleRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AuthentificationController extends AbstractController
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
     * @Route("/login", name="login")
     * @param AuthenticationUtils $authenticationUtils
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('authentification/login.html.twig', [
            'current_url' => 'login',
            'lastUsername' => $lastUsername,
            'error' => $error,
        ]);
    }


    /**
     * @Route("/inscription", name="inscription")
     * @param UserRoleRepository $repoRole
     * @param UserPasswordEncoderInterface $encoder
     * @param Request $request
     */
    public function inscription(UserPasswordEncoderInterface $encoder, Request $request, UserRoleRepository $repoRole)
    {

        $user = new User();
        $form = $this->createForm(InscriptionType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            if($request->request->get('validerReglem')) {
                $user->setRole($repoRole->find(1));
                $user->setPassword($encoder->encodePassword($user, $request->request->get('inscription')['password']['first']));
                $this->em->persist($user);
                $this->em->flush();
                $this->addFlash('success', 'Merci de vous êtes inscrits. Vous pouvez mainteant vous contectez.');
                return $this->redirectToRoute('login');
            }
            else {
                $this->addFlash('error', 'Veuillez accepter les conditions d\'utilisation !!!');
            }
        }

        return $this->render('authentification/inscription.html.twig', [
            'current_url' => 'inscription',
            'form' => $form->createView()
        ]);
    }

}
