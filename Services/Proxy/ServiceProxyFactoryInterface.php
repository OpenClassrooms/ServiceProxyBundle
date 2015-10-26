<?php

namespace OpenClassrooms\Bundle\ServiceProxyBundle\Services\Proxy;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface ServiceProxyFactoryInterface
{
    /**
     * @return \OpenClassrooms\ServiceProxy\ServiceProxyCacheInterface|\OpenClassrooms\ServiceProxy\ServiceProxyInterface
     */
    public function create($class, $tagParameters);
}
