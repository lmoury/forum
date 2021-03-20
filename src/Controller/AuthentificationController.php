<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\InscriptionType;
use App\Form\LostPasswordType;
use App\Repository\UserRoleRepository;
use App\Repository\UserRepository;
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
     * @param ObjectManager $this->em
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

    /**
     * @Route("/lost-password", name="lost.password")
     * @param ObjectManager $this->em
     * @param Request $request
     * @param UserRepository $repository
     */
    public function getEmail(UserRepository $repository, Request $request)
    {
        if('POST' === $request->getMethod() && $request->request->get('email')) {
            $user = $repository->getEmailUser(htmlspecialchars($request->request->get('email')));
            if($user){
                $longueurKey = 16; $key = "";
                for($i=1;$i<$longueurKey;$i++) {
                    $key .= rand(0,9);
                }
                $user->setLostPasswordKey($key);
                $this->em->flush();
                
                $this->addFlash('success', 'email envoyé');
                return $this->redirectToRoute('login');
            }
            $this->addFlash('error', 'Aucun compte ne correspond à cet email');
            return $this->redirectToRoute('lost.password');
        }
        return $this->render('authentification/lostPassword/email.html.twig');
    }

    /**
     * @Route("/lost-password/new", name="lost.password.new")
     * @param ObjectManager $this->em
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @param UserRepository $repository
     */
    public function lostPassword(UserRepository $repository, Request $request, UserPasswordEncoderInterface $encoder)
    {
        if($request->query->get('membre') > 0 and $request->query->get('key') > 1) {
            $user = $repository->getLostPassword($request->query->get('membre'), $request->query->get('key'));
            if($user) {
                $form = $this->createForm(LostPasswordType::class, $user);
                $form->handleRequest($request);
                if($form->isSubmitted() && $form->isValid()) {
                    $user->setPassword($encoder->encodePassword($user, $request->request->get('lost_password')['password']['first']));
                    $this->em->flush();
                    $this->addFlash('success', 'Vos mots de passe on bien été modifié');
                    return $this->redirectToRoute('login');
                }
                return $this->render('authentification/lostPassword/password.html.twig', [
                    'form' => $form->createView(),
                ]);
            }
        }
        return $this->redirectToRoute('/');
    }

}
