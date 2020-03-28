<?php
namespace App\Security\Voter;

use App\Entity\ForumCommentaire;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class LikeCommentaireVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        return $attribute === 'LIKE_COMMENTAIRE' && $subject instanceof ForumCommentaire;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        /**
        * @var $subject ForumCommentaire
        */
            foreach ($subject->getLikes() as $likes) {
                if($token->getUser() == $likes->getUser()){
                    return true;
                }
            }
    }
}
