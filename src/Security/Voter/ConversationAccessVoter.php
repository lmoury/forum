<?php
namespace App\Security\Voter;

use App\Entity\Conversation;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ConversationAccessVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        return $attribute === 'ACCESS_CONVERSATION' && $subject instanceof Conversation;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        /**
        * @var $subject Conversation
        */
        foreach ($subject->getConversationMessage() as $participant) {

            if($token->getUser() == $participant->getParticipant()){
                return true;
            }
        }
    }
}
