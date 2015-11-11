<?php

namespace OpenClassrooms\Bundle\ServiceProxyBundle\DependencyInjection;

use ProxyManager\Configuration as ProxyConfiguration;
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

        $proxyConfiguration = $this->buildProxyConfiguration(
            $container->getParameterBag()->resolveValue($config['cache_dir'])
        );
        $container->setParameter('openclassrooms.service_proxy.proxy_factory_configuration', $proxyConfiguration);
        if (in_array($container->getParameter("kernel.environment"), $config['environments'])) {
            spl_autoload_register($proxyConfiguration->getProxyAutoloader());
        }
        if (null !== $config['default_cache']) {
            $container->setParameter('openclassrooms.service_proxy.default_cache', $config['default_cache']);
        }
    }

    /**
     * @return ProxyConfiguration
     */
    private function buildProxyConfiguration($cacheDir)
    {
        if (!is_dir($cacheDir)) {
            if (false === @mkdir($cacheDir, 0777, true)) {
                throw new \RuntimeException(sprintf('Could not create cache directory "%s".', $cacheDir));
            }
        }
        $configuration = new ProxyConfiguration();
        $configuration->setProxiesTargetDir($cacheDir);

        return $configuration;
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return 'openclassrooms_service_proxy';
    }
}
