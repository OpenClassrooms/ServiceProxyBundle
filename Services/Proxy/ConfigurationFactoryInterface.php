<?php

namespace OpenClassrooms\Bundle\ServiceProxyBundle\Services\Proxy;

use ProxyManager\Configuration;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface ConfigurationFactoryInterface
{
    /**
     * @return Configuration
     */
    public function create($cacheDir, $dumpAutoload = false);
}
