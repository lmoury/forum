<?php

namespace App\Controller\Chatbox;

use App\Entity\Chatbox;
use App\Form\ChatboxType;
use App\Repository\ChatboxRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\Driver\Connection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
Use App\Twig\DateExtension;
Use App\Twig\ParserJBBCodeExtension;

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
        return $this->render('chatbox/chatbox.html.twig', [
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

    /**
     * @Route("/enregistrement", name="enregistrement")
     * @param ObjectManager $this->em
     * @param Request $request
     */
    public function enregistrement(Request $request)
    {
        $chatboxForm = new Chatbox();
        if('POST' === $request->getMethod() && $request->request->get('message') != null) {
            $chatboxForm->setPoster(new \DateTime());
            $chatboxForm->setUser($this->getUser());
            $chatboxForm->setMessage(htmlspecialchars($request->request->get('message')));
            $this->em->persist($chatboxForm);
            $this->em->flush();
        }
        return $this->redirectToRoute('chatbox');
    }

    /**
     * @Route("/charger", name="charger", methods="GET")
     * @param Request $request
     * @param DateExtension $dateChat
     * @param ParserJBBCodeExtension $parser
     * @param ChatboxRepository $repository
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function charger(Request $request, ChatboxRepository $repository, DateExtension $dateChat, ParserJBBCodeExtension $parser)
    {
        if($request->query->get('id') > 0) {
            $message = null;
            $tag = null;
            $messages = $repository->getNewMessagesChatbox($request->query->get('id'));
            if($messages != []) {
                foreach ($messages as $k => $v) {
                    if($v->getUser()->getUsername() != $this->getUser()){
                        $tag = '<span onclick="tagUser(\'message\',\''.$v->getUser()->getUsername().'\')" class="tagUser fa fa-tag"></span>';
                    }
                    $message .= '<div class="p-1" id='.$v->getId().'>
                        <img width="20" height="20"src="/data/avatar/'.$v->getUser()->getAvatar().'" alt="'.$v->getUser()->getUsername().'" />
                        <span class="chatDateCust">'.$dateChat->dateChatbox($v->getPoster()->format('Ymd H:i:s')).'</span>
                        - '.$tag.' <span class="chatpseudMessag"><a href="membres/'.$v->getUser()->getSlug().'.'.$v->getUser()->getId().'" class="pseudoUser'.$v->getUser()->getRole()->getId().'" > '.$v->getUser()->getUsername().' </a> : '.$parser->parserJBBCode($v->getMessage()).' </span>
                    </div>';
                }
                return new Response($message);
            }
        }

    }

    /**
    * @Route("/chatbox/supprimer.{id}", name="chatbox.supprimer")
    * @param ObjectManager $this->em
    * @param Chatbox $chatbox
    * @Security("is_granted('ROLE_MODERATEUR')")
    */
    public function deleteMessage(Chatbox $chatbox)
    {
        $this->em->remove($chatbox);
        $this->em->flush();
        $this->addFlash('success', 'Commentaire de la chatbox supprimé');
        return $this->redirectToRoute('chatbox');
    }





}
