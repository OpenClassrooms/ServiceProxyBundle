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
        $this->container->compile();
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

    protected function setUp()
    {
        $this->initContainer();
        $this->serviceLoader->load('services.xml');
        $this->container->compile();
    }
}
