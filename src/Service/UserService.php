<?php

namespace App\Service;

use App\Entity\User;

final class UserService
{
    public function formatUserData(User $user): array
    {
        return [
            'id'        => $user->getId(),
            'firstname' => $user->getFirstname(),
            'lastname'  => $user->getLastname(),
            'email'     => $user->getEmail(),
            'job'       => $user->getJob(),
            'linkedin'  => $user->getLinkedin(),
            'age'       => $user->getAge(),
            'city'      => $user->getCity(),
            'phone'     => $user->getPhone(),
            'profilePicture'     => $user->getProfilePicture(),
        ];
    }
}