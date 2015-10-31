<?php

namespace OpenClassrooms\Bundle\ServiceProxyBundle\Services\Proxy;

use OpenClassrooms\DoctrineCacheExtension\CacheProviderDecorator;
use OpenClassrooms\ServiceProxy\ServiceProxyBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class ServiceProxyFactory implements ServiceProxyFactoryInterface
{
    /**
     * @var ServiceProxyBuilder
     */
    private $serviceProxyBuilder;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @return \OpenClassrooms\ServiceProxy\ServiceProxyCacheInterface|\OpenClassrooms\ServiceProxy\ServiceProxyInterface
     */
    public function create($class, $tagParameters)
    {
        $builder = $this->serviceProxyBuilder->create($class);
        if (null !== $cache = $this->buildCache($tagParameters)) {
            $builder->withCache($cache);
        }

        return $builder->build();
    }

    /**
     * @return CacheProviderDecorator
     */
    private function buildCache(array $tagParameters)
    {
        $cache = null;
        if (isset($tagParameters['cache'])) {
            $cache = $this->container->get($tagParameters['cache']);
        } elseif ($this->container->hasParameter('openclassrooms.service_proxy.default_cache')) {
            $cache = $this->container->get(
                $this->container->getParameter('openclassrooms.service_proxy.default_cache')
            );
        }

        return $cache;
    }

    public function setServiceProxyBuilder(ServiceProxyBuilder $serviceProxyBuilder)
    {
        $this->serviceProxyBuilder = $serviceProxyBuilder;
    }

    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }
}
