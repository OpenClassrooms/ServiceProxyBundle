<?php

namespace OpenClassrooms\Bundle\ServiceProxyBundle\Tests\DependencyInjection;

use OpenClassrooms\Bundle\ServiceProxyBundle\Tests\ContainerTestUtil;
use OpenClassrooms\DoctrineCacheExtension\CacheProviderDecorator;
use OpenClassrooms\ServiceProxy\ServiceProxyCacheInterface;
use OpenClassrooms\ServiceProxy\ServiceProxyInterface;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class CacheOpenClassroomsServiceProxyExtensionTest extends \PHPUnit_Framework_TestCase
{
    use ContainerTestUtil;

    /**
     * @test
     * @expectedException \OpenClassrooms\Bundle\ServiceProxyBundle\Services\Proxy\NotDefinedCacheException
     */
    public function WithCacheConfigurationWithoutCacheContext_CacheUseCase_ThrowException()
    {
        $this->configLoader->load('DefaultConfiguration.yml');
        $this->container->compile();

        $this->container->get('openclassrooms.service_proxy.tests.services.cache_class_tagged_stub');
    }

    /**
     * @test
     */
    public function WithCacheConfigurationDefaultCache_CacheUseCase_ReturnUseCaseProxy()
    {
        $this->serviceLoader->load('cache_configuration_services.xml');
        $this->configLoader->load('CacheConfiguration.yml');
        $this->container->compile();

        /** @var ServiceProxyInterface|ServiceProxyCacheInterface $cacheService */
        $cacheService = $this->container->get('openclassrooms.service_proxy.tests.services.cache_class_tagged_stub');

        $this->assertServiceProxy($cacheService);
        $this->assertServiceProxyCache($cacheService);
    }

    /**
     * @test
     */
    public function WithCacheConfigurationSpecificCache_CacheUseCase_ReturnUseCaseProxy()
    {
        $this->serviceLoader->load('cache_configuration_services.xml');
        $this->configLoader->load('CacheConfiguration.yml');
        $this->container->compile();

        /** @var CacheProviderDecorator $specificCache */
        $specificCache = $this->container->get('doctrine_cache.providers.specific_array_cache');
        $specificCache->save('test', 'data');

        /** @var ServiceProxyInterface|ServiceProxyCacheInterface $cacheService */
        $cacheService = $this->container->get(
            'openclassrooms.service_proxy.tests.configuration_sepecific_cache_class_stub'
        );

        $this->assertServiceProxy($cacheService);
        $this->assertServiceProxyCache($cacheService);
        $reflectionClass = new \ReflectionClass($cacheService);
        $propertyReflection = $reflectionClass->getProperty('proxy_cacheProvider');
        $propertyReflection->setAccessible(true);
        $specificCache = $propertyReflection->getValue($cacheService);
        $specificCache->contains('test');
    }

    protected function setUp()
    {
        $this->initContainer();
        $this->serviceLoader->load('services.xml');
        $this->serviceLoader->load('cache_configuration_services.xml');
    }
}
