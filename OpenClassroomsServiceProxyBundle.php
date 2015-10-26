<?php

namespace OpenClassrooms\Bundle\ServiceProxyBundle;

use OpenClassrooms\Bundle\ServiceProxyBundle\DependencyInjection\Compiler\ServiceProxyPass;
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

        $container->addCompilerPass(new ServiceProxyPass(), PassConfig::TYPE_BEFORE_REMOVING);
    }
}
