<?php

namespace OpenClassrooms\Bundle\ServiceProxyBundle\Tests;

use OpenClassrooms\Bundle\ServiceProxyBundle\OpenClassroomsServiceProxyBundle;
use OpenClassrooms\Bundle\ServiceProxyBundle\Tests\Fixtures\Services\CacheClassStub;
use OpenClassrooms\ServiceProxy\ServiceProxyCacheInterface;
use OpenClassrooms\ServiceProxy\ServiceProxyInterface;
use PHPUnit\Framework\Assert;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
trait ContainerTestUtil
{
    /**
     * @var YamlFileLoader
     */
    protected $configLoader;

    /**
     * @var ContainerBuilder
     */
    protected $container;

    /**
     * @var string
     */
    protected static $kernelCacheDir = __DIR__ . '/cache';

    /**
     * @var XmlFileLoader
     */
    protected $serviceLoader;

    protected function initContainer()
    {
        $this->container = new ContainerBuilder();

        $this->container->setParameter('kernel.cache_dir', self::$kernelCacheDir);
        $this->container->setParameter('kernel.environment', 'test');
        $this->initServiceProxyBundle();
        $this->initServiceLoader();
        $this->initConfigLoader();
    }

    private function initServiceProxyBundle()
    {
        $bundle = new OpenClassroomsServiceProxyBundle();
        $serviceProxyExtension = $bundle->getContainerExtension();
        $this->container->registerExtension($serviceProxyExtension);
        $this->container->loadFromExtension('openclassrooms_service_proxy');
        $bundle->build($this->container);
    }

    private function initServiceLoader()
    {
        $this->serviceLoader = new XmlFileLoader(
            $this->container, new FileLocator(__DIR__ . '/Fixtures/Resources/config')
        );
        $this->serviceLoader->load('default_services.xml');
    }

    private function initConfigLoader()
    {
        $this->configLoader = new YamlFileLoader(
            $this->container,
            new FileLocator(__DIR__ . '/Fixtures/Resources/config/')
        );
        $this->configLoader->load('default_configuration.yml');
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        $fs = new Filesystem();
        if ($fs->exists(self::$kernelCacheDir)) {
            $fs->chmod(self::$kernelCacheDir, 0777);
            $fs->remove(self::$kernelCacheDir);
        }
    }

    protected function assertServiceProxy(ServiceProxyInterface $serviceProxy)
    {
        Assert::assertInstanceOf(ServiceProxyInterface::class, $serviceProxy);
    }

    protected function assertServiceProxyCache(ServiceProxyCacheInterface $serviceProxy)
    {
        Assert::assertInstanceOf(ServiceProxyCacheInterface::class, $serviceProxy);
        Assert::assertInstanceOf(CacheClassStub::class, $serviceProxy);
    }
}
