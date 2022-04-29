<?php

namespace OpenClassrooms\Bundle\ServiceProxyBundle\Tests\DependencyInjection;

use OpenClassrooms\Bundle\ServiceProxyBundle\Tests\ContainerTestUtil;
use OpenClassrooms\DoctrineCacheExtension\CacheProviderDecorator;
use OpenClassrooms\ServiceProxy\ServiceProxyCacheInterface;
use OpenClassrooms\ServiceProxy\ServiceProxyInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class CacheOpenClassroomsServiceProxyExtensionTest extends TestCase
{
    use ContainerTestUtil;

    /**
     * @test
     * @expectedException \RuntimeException
     */
    public function NotWritable_ThrowException()
    {
        $fs = new Filesystem();
        $fs->remove(self::$kernelCacheDir);
        $fs->mkdir(self::$kernelCacheDir, 0000);
        $this->initContainer();
        $this->container->compile();
        $fs->chmod(self::$kernelCacheDir, 7777);
        $fs->remove(self::$kernelCacheDir);
    }

    /**
     * @test
     */
    public function WithCacheConfigurationDefaultCache_CacheUseCase_ReturnUseCaseProxy()
    {
        $this->serviceLoader->load('cache_configuration_services.xml');
        $this->configLoader->load('cache_configuration.yml');
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
        $this->configLoader->load('cache_configuration.yml');
        $this->container->compile();

        /** @var CacheProviderDecorator $specificCache */
        $specificCache = $this->container->get('openclassrooms.service_proxy.tests.cache.specific');
        $specificCache->save('test', 'data');

        /** @var ServiceProxyInterface|ServiceProxyCacheInterface $cacheService */
        $cacheService = $this->container->get(
            'openclassrooms.service_proxy.tests.configuration_specific_cache_class_stub'
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
