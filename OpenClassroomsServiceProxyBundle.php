<?php

namespace OpenClassrooms\Bundle\ServiceProxyBundle;

use OpenClassrooms\Bundle\ServiceProxyBundle\DependencyInjection\Compiler\ServiceProxyPass;
use OpenClassrooms\Bundle\ServiceProxyBundle\DependencyInjection\OpenClassroomsServiceProxyExtension;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class OpenClassroomsServiceProxyBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new ServiceProxyPass(), PassConfig::TYPE_AFTER_REMOVING, 1);
    }

    /**
     * {@inheritdoc}
     */
    public function getContainerExtension()
    {
        return new OpenClassroomsServiceProxyExtension();
    }
}
