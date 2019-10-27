<?php
namespace App\Security\Voter;

use App\Entity\ForumCommentaire;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ForumCommentaireVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        return $attribute === 'DELET_EDIT_COMMENTAIRE' && $subject instanceof ForumCommentaire;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        /** @var $subject ForumCommentaire */
        return $subject->getAuteur()->getId() === $token->getUser()->getId();
    }
}
