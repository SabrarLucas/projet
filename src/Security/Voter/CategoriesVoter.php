<?php

namespace App\Security\Voter;

use App\Entity\Categories;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class CategoriesVoter extends Voter
{
    const EDIT = 'CATEGORIE_EDIT';
    const DELETE = 'CATEGORIE_DELETE';

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $categorie): bool
    {
        if(!in_array($attribute, [self::EDIT, self::DELETE])){
            return false;
        }
        if(!$categorie instanceof Categories){
            return false;
        }
        return true;
    }

    protected function voteOnAttribute($attribute, $categorie, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if(!$user instanceof UserInterface) return false;

        if($this->security->isGranted('ROLE_ADMIN')) return true;

        switch($attribute){
            case self::EDIT:
                // On vérifie si l'utilisateur peut éditer
                return $this->canEdit();
                break;
            case self::DELETE:
                // On vérifie si l'utilisateur peut supprimier
                return $this->canDelete();
                break;

        }
    }

    private function canEdit(){
        Return $this->security->IsGranted('ROLE_PRODUCT_ADMIN');
    }

    private function canDelete(){
        Return $this->security->IsGranted('ROLE_PRODUCT_ADMIN');
    }
}