<?php

namespace OpenClassrooms\Bundle\ServiceProxyBundle\Services\Proxy;

use OpenClassrooms\DoctrineCacheExtension\CacheProviderDecorator;
use OpenClassrooms\ServiceProxy\ServiceProxyCacheInterface;
use OpenClassrooms\ServiceProxy\ServiceProxyFactoryInterface as BaseServiceProxyFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class ServiceProxyFactory implements ServiceProxyFactoryInterface
{
    /**
     * @var BaseServiceProxyFactoryInterface
     */
    private $baseServiceProxyFactory;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @return \OpenClassrooms\ServiceProxy\ServiceProxyCacheInterface|\OpenClassrooms\ServiceProxy\ServiceProxyInterface
     */
    public function create($class, $tagParameters)
    {
        $proxy = $this->baseServiceProxyFactory->createSimpleProxy($class);
        if ($proxy instanceof ServiceProxyCacheInterface) {
            $proxy->proxy_setCacheProvider($this->buildCache($class, $tagParameters));
        }

        return $proxy;
    }

    /**
     * @return CacheProviderDecorator
     *
     * @throws NotDefinedCacheException
     */
    private function buildCache($class, array $tagParameters)
    {
        if (isset($tagParameters['cache'])) {
            $cache = $this->container->get($tagParameters['cache']);
        } elseif ($this->container->hasParameter('openclassrooms.service_proxy.default_cache')) {
            $cache = $this->container->get(
                $this->container->getParameter('openclassrooms.service_proxy.default_cache')
            );
        } else {
            throw new NotDefinedCacheException('Cache should be defined for class: '.get_class($class).'.');
        }

        return $cache;
    }

    public function setBaseServiceProxyFactory(BaseServiceProxyFactoryInterface $baseServiceProxyFactory)
    {
        $this->baseServiceProxyFactory = $baseServiceProxyFactory;
    }

    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }
}
