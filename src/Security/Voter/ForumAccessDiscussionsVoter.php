<?php
namespace App\Security\Voter;

use App\Entity\ForumCategorie;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class ForumAccessDiscussionsVoter extends Voter
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
        return $attribute === 'ACCESS_DISCUSSIONS' && $subject instanceof ForumCategorie;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        /**
        * @var $subject ForumCategorie
        */
        if($this->security->isGranted('ROLE_USER')) {
            if($subject->getAccess() == [] or $this->security->isGranted($subject->getAccess()->getRole())) {
                return true;
            }
        }
    }
}
