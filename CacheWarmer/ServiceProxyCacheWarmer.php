<?php

namespace OpenClassrooms\Bundle\ServiceProxyBundle\CacheWarmer;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmer;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class ServiceProxyCacheWarmer extends CacheWarmer
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritdoc}
     */
    public function warmUp($cacheDir)
    {
        $serviceProxyIds = $this->container->getParameter('openclassrooms.service_proxy.service_proxy_ids');
        foreach ($serviceProxyIds as $serviceProxyId) {
            $this->container->get($serviceProxyId);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isOptional()
    {
        return false;
    }

    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }
}
