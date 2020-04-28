<?php
namespace App\Security\Voter;

use App\Entity\ForumDiscussion;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class LikeDiscussionVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        return $attribute === 'LIKE_DISCUSSION' && $subject instanceof ForumDiscussion;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        /**
        * @var $subject ForumDiscussion
        */
            foreach ($subject->getLikes() as $likeDiscu) {
                if($token->getUser() == $likeDiscu->getUser()){
                    return true;
                }
            }
    }
}
