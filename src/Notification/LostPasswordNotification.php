<?php

namespace App\Notification;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LostPasswordNotification
{

    /**
     * @Route("/login", name="login")
     * @param User $user
     */
    public function sendEmailLostPassword(User $user)
    {
        dump('ici');
        die();
    }

}
