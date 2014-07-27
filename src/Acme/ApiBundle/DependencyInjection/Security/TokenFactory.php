<?php

namespace Acme\ApiBundle\DependencyInjection\Security;

use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;

class TokenFactory implements SecurityFactoryInterface
{
    public function create(ContainerBuilder $container, $id, $config, $userProvider, $defaultEntryPoint)
    {
        $providerId = 'token_authentication_provider_' . $id;
        $container
            ->setDefinition($providerId, new DefinitionDecorator('token_authentication_provider'))
            ->replaceArgument(2, $id)
        ;

        $listenerId = 'token_authentication_listener_'.$id;
        $container
            ->setDefinition($listenerId, new DefinitionDecorator('token_authentication_listener'))
            ->replaceArgument(2, $id)
        ;

        return array($providerId, $listenerId, $defaultEntryPoint);
    }

    public function getPosition()
    {
        return 'http';
    }

    public function getKey()
    {
        return 'token';
    }

    public function addConfiguration(NodeDefinition $builder)
    {
        // noop
    }
}