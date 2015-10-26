<?php

namespace OpenClassrooms\Bundle\ServiceProxyBundle\Tests\DependencyInjection;

use OpenClassrooms\Bundle\ServiceProxyBundle\Tests\DependencyInjection\Fixtures\Services\ClassStub;
use OpenClassrooms\ServiceProxy\ServiceProxyInterface;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class OpenClassroomsServiceProxyExtensionTest extends AbstractDependencyInjectionTest
{
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
        /** @var ServiceProxyInterface $actualClass */
        $actualClass = $this->container->get('openclassrooms.service_proxy.tests.services.class_tagged_stub');
        $this->assertServiceProxy($actualClass);
    }

    protected function setUp()
    {
        $this->initContainer();
        $this->container->compile();
    }
}
