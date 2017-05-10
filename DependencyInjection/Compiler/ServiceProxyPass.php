<?php

namespace OpenClassrooms\Bundle\ServiceProxyBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\Compiler;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\LoggingFormatter;
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

    /**
     * @var Compiler
     */
    private $compiler;

    /**
     * @var LoggingFormatter
     */
    private $formatter;

    public function process(ContainerBuilder $container)
    {
        $this->container = $container;
        $this->compiler = $container->getCompiler();
        $this->formatter = $this->compiler->getLoggingFormatter();

        $this->buildServiceProxies();
    }

    private function buildServiceProxies()
    {
        $serviceProxyIds = [];

        $taggedServices = $this->container->findTaggedServiceIds('openclassrooms.service_proxy');
        foreach ($taggedServices as $taggedServiceName => $tagParameters) {
            $this->buildServiceProxyFactoryDefinition($taggedServiceName, $tagParameters);
            $serviceProxyIds[] = $taggedServiceName;
            $this->compiler->addLogMessage($this->formatter->format($this, 'Add proxy for ' . $taggedServiceName . ' service.'));
        }
        $this->container->setParameter('openclassrooms.service_proxy.service_proxy_ids', $serviceProxyIds);
    }

    private function buildServiceProxyFactoryDefinition($taggedServiceName, $tagParameters)
    {
        $definition = $this->container->findDefinition($taggedServiceName);

        $factoryDefinition = new Definition();
        $factoryDefinition->setFactory([new Reference('openclassrooms.service_proxy.service_proxy_factory'), 'create']);
        $factoryDefinition->setArguments([$definition, $tagParameters[0]]);
        $this->container->setDefinition($taggedServiceName, $factoryDefinition);
    }
}
