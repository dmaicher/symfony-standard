<?php

namespace Acme\ApiBundle\Security\User;

use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class ApiKeyUserProvider implements UserProviderInterface
{
    public function getUsernameForApiKey($apiKey)
    {
        if ($apiKey === '123abc') {
            return 'user1';
        }

        if ($apiKey === 'def456') {
            return 'user2';
        }

        return null;
    }

    public function loadUserByUsername($username)
    {
        switch ($username) {
            case 'user1':
                $roles = array('ROLE_API_USER');
                break;
            case 'user2':
                $roles = array('ROLE_USER');
                break;
            default:
                throw new UsernameNotFoundException();
        }

        return new User($username, null, $roles);
    }

    public function refreshUser(UserInterface $user)
    {
        throw new UnsupportedUserException();
    }

    public function supportsClass($class)
    {
        return 'Symfony\Component\Security\Core\User\User' === $class;
    }
}
