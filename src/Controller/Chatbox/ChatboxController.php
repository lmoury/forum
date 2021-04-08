<?php

namespace App\Controller\Chatbox;

use App\Entity\Chatbox;
use App\Form\ChatboxType;
use App\Repository\ChatboxRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Driver\Connection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
Use App\Twig\DateExtension;
Use App\Twig\ParserJBBCodeExtension;

class ChatboxController extends AbstractController
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
     * @Security("is_granted('ROLE_USER')")
     * @param ChatboxRepository $repository
     */
    public function chatbox(ChatboxRepository $repository)
    {
        $chatbox = $repository->getChatbox();
        return $this->render('chatbox/chatbox.html.twig', [
            'chatbox' => $chatbox
        ]);
    }

    /**
     * @Route("/enregistrement", name="enregistrement")
     * @Security("is_granted('ROLE_USER')")
     * @param EntityManagerInterface $this->em
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
        return $this->redirectToRoute('forums');
    }

    /**
    * @Route("/chatbox/delete", name="chatbox.delete", methods="DELETE")
    * @param EntityManagerInterface $this->em
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

        return $this->redirectToRoute('forums');
    }

    /**
    * @Route("/chatbox/supprimer.{id}", name="chatbox.supprimer", methods="DELETE")
    * @param EntityManagerInterface $this->em
    * @param Chatbox $chatbox
    * @param Request $request
    * @Security("is_granted('ROLE_MODERATEUR')")
    */
    public function deleteMessage(Chatbox $chatbox, Request $request)
    {
        if($this->isCsrfTokenValid('delete' . $chatbox->getId(), $request->get('_token'))) {
            $this->em->remove($chatbox);
            $this->em->flush();
            $this->addFlash('success', 'Commentaire de la chatbox supprimé');
        }
        return $this->redirectToRoute('forums');
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
        if(!$this->getUser()) {
            return die();
        }
        if($request->query->get('id') > 0) {
            $message = null; $signalement = null; $tag = null; $delete = null; $texte = null;
            $messages = $repository->getNewMessagesChatbox($request->query->get('id'));

            if($messages != []) {
                foreach ($messages as $k => $v) {
                    if($v->getUser()->getUsername() != $this->getUser()){
                        $tag = '<span onclick="tagUser(\'message\',\''.$v->getUser()->getUsername().'\')" class="tagUser fa fa-tag"></span>';
                        $signalement = '<a href="#" class="float-right" data-title="Signaler" data-toggle="modal" data-target="#signaler'.$v->getId().'" ><i class="fa fa-exclamation-triangle"></i></a>
                        <div class="modal fade modal-custom" id="signaler'.$v->getId().'" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
                            '.$this->render('signalement/signalement_modal.html.twig', ['route' => 'signalement.chatbox', 'signal' => $v->getId()]).'
                        </div>';
                    }
                    if($this->getUser()->getRole()->getId() == 3 or $this->getUser()->getRole()->getId() == 2) {
                        $delete = '<a href="#" data-title="Delete" data-toggle="modal" data-target="#delete'.$v->getId().'" ><i class="fa fa-trash"></i></a>
                        <div class="modal fade" id="delete'.$v->getId().'" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
                            '.$this->render('inc/modal/modal_delete.html.twig', ['route' => 'chatbox.supprimer', 'signal' => $v->getId()]).'
                        </div>';
                    }
                    if ($v->getUser()->getRole()->getId() == 1 or $v->getUser()->getRole()->getId() == 5) {
                        $texte = $v->getMessage();
                    }
                    else {
                        $texte = $parser->parserJBBCode($v->getMessage());
                    }
                    $message .= '<div class="p-1" id='.$v->getId().'>
                        '.$signalement.'
                        '.$delete.'
                        <img width="20" height="20"src="/data/avatar/'.$v->getUser()->getAvatar().'" alt="'.$v->getUser()->getUsername().'" />
                        <span class="chatDateCust">'.$dateChat->dateChatbox($v->getPoster()->format('Ymd H:i:s')).'</span>
                        - '.$tag.' <span class="chatpseudMessag"><a href="membres/'.$v->getUser()->getSlug().'.'.$v->getUser()->getId().'" class="pseudoUser'.$v->getUser()->getRole()->getId().'" > '.$v->getUser()->getUsername().' </a> : '.$texte.' </span>
                    </div>';
                }
                return new Response($message);
            }
        }

    }
}
