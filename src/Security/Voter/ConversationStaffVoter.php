<?php
namespace App\Security\Voter;

use App\Entity\Conversation;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ConversationStaffVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        return $attribute === 'STAFF_CONVERSATION' && $subject instanceof Conversation;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {

        /**
        * @var $subject Conversation
        */
        foreach ($subject->getConversationMessage() as $participant) {
            if($participant->getParticipant()->getRole()->getId() == 3 or $participant->getParticipant()->getRole()->getId() == 2){
                return true;
            }
        }
    }
}
