<?php

namespace App\Controller\Chatbox;

use App\Entity\Chatbox;
use App\Form\ChatboxType;
use App\Repository\ChatboxRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\Driver\Connection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Security("is_granted('ROLE_USER')")
 */
class ChatboxController extends AbstractController
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
     * @Route("/chatbox", name="chatbox")
     * @param ObjectManager $this->em
     * @param ChatboxRepository $repository
     * @param Request $request
     */
    public function chatbox(Request $request, ChatboxRepository $repository)
    {

        $chatbox = $repository->findAll();
        $chatboxForm = new Chatbox();
        $form = $this->createForm(ChatboxType::class, $chatboxForm);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $chatboxForm->setPoster(new \DateTime());
            $chatboxForm->setUser($this->getUser());
            $chatboxForm->setMessage(htmlspecialchars($chatboxForm->getMessage()));
            $this->em->persist($chatboxForm);
            $this->em->flush();
            return $this->redirectToRoute('chatbox');
        }

        return $this->render('chatbox/chatbox.html.twig', [
            'form' => $form->createView(),
            'chatbox' => $chatbox
        ]);
    }


    /**
     * @Route("/loadChatbox", name="loadChatbox")
     * @param ChatboxRepository $repository
     */
    public function loadChatbox(ChatboxRepository $repository)
    {

        $chatbox = $repository->findAll();
        return $this->render('chatbox/load_chatbox.html.twig', [
            'chatbox' => $chatbox
        ]);
    }

    /**
    * @Route("/chatbox/delete", name="chatbox.delete", methods="DELETE")
    * @param ObjectManager $this->em
    * @param Connection $connection
    * @Security("is_granted('ROLE_MODERATEUR')")
    */
    public function delete(Connection $connection)
    {
        $platform   = $connection->getDatabasePlatform();
        $connection->executeUpdate($platform->getTruncateTableSQL('chatbox', true /* whether to cascade */));

        $chatbox = new Chatbox();
        $chatbox->setPoster(new \DateTime());
        $chatbox->setUser($this->getUser());
        $chatbox->setMessage('a vidé la chatbox :nettoyage:');
        $this->em->persist($chatbox);
        $this->em->flush();

        return $this->redirectToRoute('chatbox');
    }



}
