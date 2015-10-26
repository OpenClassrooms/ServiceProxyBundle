<?php

namespace OpenClassrooms\Bundle\ServiceProxyBundle\Tests\DependencyInjection;

use Doctrine\Bundle\DoctrineCacheBundle\DependencyInjection\DoctrineCacheExtension;
use Doctrine\Bundle\DoctrineCacheBundle\DoctrineCacheBundle;
use OpenClassrooms\Bundle\DoctrineCacheExtensionBundle\DependencyInjection\OpenClassroomsDoctrineCacheExtensionExtension;
use OpenClassrooms\Bundle\DoctrineCacheExtensionBundle\OpenClassroomsDoctrineCacheExtensionBundle;
use OpenClassrooms\Bundle\ServiceProxyBundle\DependencyInjection\OpenClassroomsServiceProxyExtension;
use OpenClassrooms\Bundle\ServiceProxyBundle\OpenClassroomsServiceProxyBundle;
use OpenClassrooms\Bundle\ServiceProxyBundle\Tests\DependencyInjection\Fixtures\Services\CacheClassStub;
use OpenClassrooms\ServiceProxy\ServiceProxyCacheInterface;
use OpenClassrooms\ServiceProxy\ServiceProxyInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
abstract class AbstractDependencyInjectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ContainerBuilder
     */
    protected $container;

    /**
     * @var YamlFileLoader
     */
    protected $configLoader;

    /**
     * @var XmlFileLoader
     */
    protected $serviceLoader;

    protected function initContainer()
    {
        $this->container = new ContainerBuilder();

        $this->initDoctrineCacheBundle();
        $this->initDoctrineCacheExtensionBundle();
        $this->initServiceProxyBundle();
        $this->initServiceLoader();
        $this->initConfigLoader();
    }

    protected function initDoctrineCacheBundle()
    {
        $doctrineCacheExtension = new DoctrineCacheExtension();
        $this->container->registerExtension($doctrineCacheExtension);
        $this->container->loadFromExtension('doctrine_cache');
        $bundle = new DoctrineCacheBundle();
        $bundle->build($this->container);
    }

    protected function initDoctrineCacheExtensionBundle()
    {
        $doctrineCacheExtensionExtension = new OpenClassroomsDoctrineCacheExtensionExtension();
        $this->container->registerExtension($doctrineCacheExtensionExtension);
        $this->container->loadFromExtension('doctrine_cache');
        $bundle = new OpenClassroomsDoctrineCacheExtensionBundle();
        $bundle->build($this->container);
    }

    protected function initServiceProxyBundle()
    {
        $serviceProxyExtension = new OpenClassroomsServiceProxyExtension();
        $this->container->registerExtension($serviceProxyExtension);
        $this->container->loadFromExtension('openclassrooms_service_proxy');
        $bundle = new OpenClassroomsServiceProxyBundle();
        $bundle->build($this->container);
    }

    protected function initServiceLoader()
    {
        $this->serviceLoader = new XmlFileLoader(
            $this->container, new FileLocator(__DIR__.'/Fixtures/Resources/config')
        );
        $this->serviceLoader->load('services.xml');
    }

    protected function initConfigLoader()
    {
        $this->configLoader = new YamlFileLoader(
            $this->container,
            new FileLocator(__DIR__.'/Fixtures/Resources/config/')
        );
        $this->configLoader->load('DefaultConfiguration.yml');
    }

    protected function assertServiceProxy(ServiceProxyInterface $serviceProxy)
    {
        $this->assertInstanceOf(ServiceProxyInterface::class, $serviceProxy);
    }

    protected function assertServiceProxyCache(ServiceProxyCacheInterface $serviceProxy)
    {
        $this->assertInstanceOf(ServiceProxyCacheInterface::class, $serviceProxy);
        $this->assertInstanceOf(CacheClassStub::class, $serviceProxy);
    }
}
