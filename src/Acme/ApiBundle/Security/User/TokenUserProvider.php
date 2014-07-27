<?php

namespace Acme\ApiBundle\Security\User;

use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class TokenUserProvider implements UserProviderInterface
{
    public function loadUserByUsername($username)
    {
        switch ($username) {
            case 'user1':
                $roles    = array('ROLE_API_USER');
                break;
            case 'user2':
                $roles    = array('ROLE_USER');
                break;
            default:
                throw new UsernameNotFoundException();
        }

        return new User($username, '1234', $roles);
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