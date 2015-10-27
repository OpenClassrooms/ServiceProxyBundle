<?php

namespace CacheWarmer;

use OpenClassrooms\Bundle\ServiceProxyBundle\CacheWarmer\ServiceProxyCacheWarmer;
use OpenClassrooms\Bundle\ServiceProxyBundle\Tests\ContainerTestUtil;
use Symfony\Component\Finder\Finder;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class ServiceProxyCacheWarmerTest extends \PHPUnit_Framework_TestCase
{
    use ContainerTestUtil;

    /**
     * @test
     */
    public function WarmupIsNotOptional()
    {
        $serviceProxyCacheWarmer = new ServiceProxyCacheWarmer();
        $this->assertFalse($serviceProxyCacheWarmer->isOptional());
    }

    /**
     * @test
     */
    public function NoService_CacheEmpty()
    {
        $this->initContainer();
        $this->container->compile();

        $serviceProxyCacheWarmer = new ServiceProxyCacheWarmer();
        $serviceProxyCacheWarmer->setContainer($this->container);
        $serviceProxyCacheWarmer->warmUp(self::$kernelCacheDir);

        $this->assertEquals(0, Finder::create()->in(self::$kernelCacheDir.'/openclassrooms_service_proxy')->count());
    }

    /**
     * @test
     */
    public function WithServices_Cache()
    {
        $this->initContainer();
        $this->serviceLoader->load('cache_configuration_services.xml');
        $this->configLoader->load('CacheConfiguration.yml');
        $this->container->compile();

        $serviceProxyCacheWarmer = new ServiceProxyCacheWarmer();
        $serviceProxyCacheWarmer->setContainer($this->container);
        $serviceProxyCacheWarmer->warmUp(self::$kernelCacheDir);

        $this->assertEquals(1, Finder::create()->in(self::$kernelCacheDir.'/openclassrooms_service_proxy')->count());
    }
}
