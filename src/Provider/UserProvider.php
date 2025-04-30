<?php

namespace App\Provider;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class UserProvider
{
    private Security $security;
    private EntityManagerInterface $em;

    public function __construct(Security $security, EntityManagerInterface $em)
    {
        $this->security = $security;
        $this->em = $em;
    }

    public function getCurrentUser(): ?User
    {
        $user = $this->security->getUser();
        if (!$user || !$user instanceof User) {
            return null;
        }
        
        return $this->em->getRepository(User::class)->find($user->getId());
    }
}