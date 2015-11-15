<?php

namespace OpenClassrooms\Bundle\ServiceProxyBundle\Tests\DependencyInjection;

use OpenClassrooms\Bundle\ServiceProxyBundle\Tests\ContainerTestUtil;
use OpenClassrooms\Bundle\ServiceProxyBundle\Tests\Fixtures\Services\ClassStub;
use OpenClassrooms\Bundle\ServiceProxyBundle\Tests\Fixtures\Services\ClassTaggedStub;
use OpenClassrooms\ServiceProxy\ServiceProxyInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class OpenClassroomsServiceProxyExtensionTest extends \PHPUnit_Framework_TestCase
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
        $this->serviceLoader->load('services.xml');
        $this->container->compile();
        $this->container->get('openclassrooms.service_proxy.tests.services.class_tagged_stub');
        $fs->chmod(self::$kernelCacheDir, 7777);
        $fs->remove(self::$kernelCacheDir);
    }

    /**
     * @test
     */
    public function GetClass()
    {
        $actualClass = $this->container->get('openclassrooms.service_proxy.tests.services.class_stub');
        $this->assertEquals(new ClassStub(), $actualClass);
    }

    /**
     * @test
     */
    public function ClassTagged_ReturnProxy()
    {
        /** @var ServiceProxyInterface|ClassTaggedStub $actualClass */
        $actualClass = $this->container->get('openclassrooms.service_proxy.tests.services.class_tagged_stub');
        $this->assertServiceProxy($actualClass);
        $this->assertNotNull($actualClass->aMethod());
    }

    /**
     * @test
     */
    public function TestEnvironment_NotRegisteredLoader()
    {
        $this->initContainer();
        $this->serviceLoader->load('services.xml');
        $this->container->setParameter('kernel.environment', 'test');
        $this->container->compile();
        $this->container->get('openclassrooms.service_proxy.tests.services.class_tagged_stub');
        $this->assertCount(1, spl_autoload_functions());
        $this->assertNotInstanceOf('ProxyManager\Autoloader\AutoloaderInterface', spl_autoload_functions()[0]);
    }

    /**
     * @test
     */
    public function DefaultEnvironment_RegisterLoader()
    {
        $this->initContainer();
        $this->serviceLoader->load('services.xml');
        $this->container->setParameter('kernel.environment', 'prod');
        $this->container->compile();
        $this->container->get('openclassrooms.service_proxy.tests.services.class_tagged_stub');
        $this->assertInstanceOf('ProxyManager\Autoloader\AutoloaderInterface', spl_autoload_functions()[1]);
    }

    /**
     * @test
     */
    public function Environment_RegisterLoader()
    {
        $this->initContainer();
        $this->serviceLoader->load('services.xml');
        $this->configLoader->load('production_environments_config.yml');
        $this->container->setParameter('kernel.environment', 'test');
        $this->container->compile();
        $this->container->get('openclassrooms.service_proxy.tests.services.class_tagged_stub');
        $this->assertInstanceOf('ProxyManager\Autoloader\AutoloaderInterface', spl_autoload_functions()[1]);
    }

    protected function setUp()
    {
        $this->initContainer();
        $this->serviceLoader->load('services.xml');
        $this->container->compile();
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
        if (isset(spl_autoload_functions()[1])) {
            spl_autoload_unregister(spl_autoload_functions()[1]);
        }
    }
}
