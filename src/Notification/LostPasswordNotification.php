<?php

namespace App\Notification;

use App\Entity\User;
use Twig\Environment;

class LostPasswordNotification
{

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var Environment
     */
    private $renderer;

    public function __construct(\Swift_Mailer $mailer,Environment $renderer)
    {
            $this->mailer = $mailer;
            $this->renderer = $renderer;
    }

    /**
     * @param User $user
     */
    public function sendEmailLostPassword(User $user)
    {
        $message = (new \Swift_Message('Récupération de mot de passe'))
        ->setFrom('laurentmoury@gmail.com')
        ->setTo($user->getEmail())
        ->setBody($this->renderer->render('emails/lostPassword.html.twig', [
            'user' => $user,
        ]), 'text/html');
        $this->mailer->send($message);

    }

}
