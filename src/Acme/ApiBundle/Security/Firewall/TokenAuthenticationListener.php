<?php

namespace Acme\ApiBundle\Security\Firewall;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\AuthenticationServiceException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;

class TokenAuthenticationListener implements ListenerInterface
{
    /**
     * @var SecurityContextInterface
     */
    protected $securityContext;

    /**
     * @var AuthenticationManagerInterface
     */
    protected $authenticationManager;

    /**
     * @var string
     */
    protected $providerKey;

    /**
     * @param SecurityContextInterface       $securityContext
     * @param AuthenticationManagerInterface $authenticationManager
     * @param string                         $providerKey
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(SecurityContextInterface $securityContext, AuthenticationManagerInterface $authenticationManager, $providerKey)
    {
        $this->securityContext       = $securityContext;
        $this->authenticationManager = $authenticationManager;
        $this->providerKey           = $providerKey;
    }

    public function handle(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        $data = json_decode($request->getContent(), true);

        if (is_array($data) && $token = $this->createToken($data)) {
            try {
                $token = $this->authenticationManager->authenticate($token);
                $this->securityContext->setToken($token);

                return;
            }
            catch(AuthenticationException $e) {}
        }

        $event->setResponse(new Response('', Response::HTTP_UNAUTHORIZED));
    }

    /**
     * Parses JSON data and creates a username/password token
     *
     * @param array $data
     *
     * @return UsernamePasswordToken|null
     */
    protected function createToken(array $data)
    {
        if (!isset($data['username']) || !isset($data['password'])) {
            return null;
        }

        $username = $data['username'];
        $password = $data['password'];

        return new UsernamePasswordToken($username, $password, $this->providerKey);
    }
}