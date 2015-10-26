<?php

namespace OpenClassrooms\Bundle\ServiceProxyBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class OpenClassroomsServiceProxyExtension extends Extension
{
    public function load(array $config, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config/'));
        $loader->load('services.xml');

        $config = $this->processConfiguration(new Configuration(), $config);
        if (null !== $config['default_cache']) {
            $container->setParameter('openclassrooms.service_proxy.default_cache', $config['default_cache']);
        }
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return 'openclassrooms_service_proxy';
    }
}
