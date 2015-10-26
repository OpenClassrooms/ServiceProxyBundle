<?php

namespace OpenClassrooms\Bundle\ServiceProxyBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class ServiceProxyPass implements CompilerPassInterface
{
    /**
     * @var ContainerBuilder
     */
    private $container;

    public function process(ContainerBuilder $container)
    {
        $this->container = $container;
        $this->buildServiceProxies();
    }

    private function buildServiceProxies()
    {
        $taggedServices = $this->container->findTaggedServiceIds('openclassrooms.service_proxy');

        foreach ($taggedServices as $taggedServiceName => $tagParameters) {
            $this->buildServiceProxyFactoryDefinition($taggedServiceName, $tagParameters);
        }
    }

    private function buildServiceProxyFactoryDefinition($taggedServiceName, $tagParameters)
    {
        $definition = $this->container->findDefinition($taggedServiceName);
        $factoryDefinition = new Definition();
        $factoryDefinition->setFactory([new Reference('openclassrooms.service_proxy.service_proxy_factory'), 'create']);
        $factoryDefinition->setArguments(array($definition, $tagParameters[0]));
        $this->container->setDefinition($taggedServiceName, $factoryDefinition);
    }
}
