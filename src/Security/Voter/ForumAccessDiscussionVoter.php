<?php
namespace App\Security\Voter;

use App\Entity\ForumDiscussion;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class ForumAccessDiscussionVoter extends Voter
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
        return $attribute === 'ACCESS_DISCUSSION' && $subject instanceof ForumDiscussion;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        /**
        * @var $subject ForumCategorie
        */
        if($this->security->isGranted('ROLE_USER')) {
            if($subject->getCategorie()->getAccess() == [] or $this->security->isGranted($subject->getCategorie()->getAccess()->getRole())) {
                return true;
            }
        }
    }
}
