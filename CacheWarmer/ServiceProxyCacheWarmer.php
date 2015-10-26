<?php

namespace OpenClassrooms\Bundle\ServiceProxyBundle\CacheWarmer;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmer;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class ServiceProxyCacheWarmer extends CacheWarmer
{
    /**
     * @var string[]
     */
    public static $serviceProxyIds = [];

    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct()
    {
        self::$serviceProxyIds = [];
    }

    /**
     * @inheritDoc
     */
    public function warmUp($cacheDir)
    {
        foreach (self::$serviceProxyIds as $serviceProxyId) {
            $this->container->get($serviceProxyId);
        }
    }

    /**
     * @inheritDoc
     */
    public function isOptional()
    {
        return false;
    }

    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }
}
