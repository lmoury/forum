<?php
namespace App\Security\Voter;

use App\Entity\ForumDiscussion;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ForumDiscussionVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        return $attribute === 'DELET_EDIT_DISCISSION' && $subject instanceof ForumDiscussion;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        /**
        * @var $subject ForumDiscussion 
        */
        return $subject->getAuteur()->getId() === $token->getUser()->getId();
    }
}
