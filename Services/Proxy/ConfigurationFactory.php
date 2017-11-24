<?php

namespace OpenClassrooms\Bundle\ServiceProxyBundle\Services\Proxy;

use ProxyManager\Configuration;
use ProxyManager\FileLocator\FileLocator;
use ProxyManager\GeneratorStrategy\FileWriterGeneratorStrategy;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class ConfigurationFactory implements ConfigurationFactoryInterface
{
    /**
     * @return Configuration
     */
    public function create($cacheDir, $dumpAutoload = false)
    {
        if (!is_dir($cacheDir)) {
            if (false === @mkdir($cacheDir, 0777, true)) {
                throw new \RuntimeException(sprintf('Could not create cache directory "%s".', $cacheDir));
            }
        }
        $configuration = new Configuration();
        $configuration->setProxiesTargetDir($cacheDir);

        if ($dumpAutoload) {
            $configuration->setGeneratorStrategy(
                new FileWriterGeneratorStrategy(new FileLocator($cacheDir))
            );
            spl_autoload_register($configuration->getProxyAutoloader());
        }

        return $configuration;
    }
}
