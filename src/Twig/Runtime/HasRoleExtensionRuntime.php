<?php

namespace App\Twig\Runtime;

use Twig\Extension\RuntimeExtensionInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class HasRoleExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct()
    {
        // Inject dependencies if needed
    }

    public function hasRole(UserInterface $user, string $role)
    {
        // ...
        return true;
    }
}
