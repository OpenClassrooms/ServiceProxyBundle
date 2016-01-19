# ServiceProxyBundle
[![Build Status](https://travis-ci.org/OpenClassrooms/ServiceProxyBundle.svg?branch=master)](https://travis-ci.org/OpenClassrooms/ServiceProxyBundle)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/68f0b7d3-8e1f-4f8c-a84d-4c72f4cec6d1/mini.png)](https://insight.sensiolabs.com/projects/68f0b7d3-8e1f-4f8c-a84d-4c72f4cec6d1)
[![Coverage Status](https://coveralls.io/repos/OpenClassrooms/ServiceProxyBundle/badge.svg?branch=master&service=github)](https://coveralls.io/github/OpenClassrooms/ServiceProxyBundle?branch=master)

The ServiceProxyBundle offers integration of the ServiceProxy library.  
ServiceProxy provides functionality to manage technical code over a class:  
* Transactional context (not implemented yet)
* Security access (not implemented yet)
* Cache management
* Events (not implemented yet)
* Logs (not implemented yet)
    
See [ServiceProxy](https://github.com/OpenClassrooms/ServiceProxy) for full details.

## Installation
This bundle can be installed using composer:  

```composer require openclassrooms/service-proxy-bundle```  
or by adding the package to the composer.json file directly.

```json
{
    "require": {
        "openclassrooms/service-proxy-bundle": "*"
    }
}
```

After the package has been installed, add the bundle to the AppKernel.php file:

```php
// in AppKernel::registerBundles()
$bundles = array(
    // ...
    new OpenClassrooms\Bundle\ServiceProxyBundle\OpenClassroomsServiceProxyBundle(),
    // ...
);
```

:warning: The usage of cache capabilities requires the installation of the  [openclassrooms/doctrine-cache-extension-bundle](https://github.com/OpenClassrooms/DoctrineCacheExtensionBundle).  
See the [openclassrooms/doctrine-cache-extension-bundle installation guide](https://github.com/OpenClassrooms/DoctrineCacheExtensionBundle#installation)for more details.

## Configuration

```yaml

# app/config/config.yml

openclassrooms_service_proxy: ~

```

[Full configuration](https://github.com/OpenClassrooms/ServiceProxyBundle#full-configuration)


## Usage

### Cache

Use the Cache annotation.  
See [Service Proxy Cache](https://github.com/OpenClassrooms/ServiceProxy#cache) for more details.

``` php
<?php

namespace A\Namespace;

use OpenClassrooms\ServiceProxy\Annotations\Cache;

class AClass
{
    /**
     * @Cache
     */
    public function aMethod()
    {
        // do things
    }
}
```

#### Using default cache provider

``` yaml

# app/config/config.yml

doctrine_cache:
    providers:
        a_cache_provider:
            type: array

openclassrooms_service_proxy: 
    default_cache: doctrine_cache.providers.a_cache_provider    
```

```xml
<!-- services.xml -->
<service id="a_service" class="A\Namespace\AClass">
    <tag name="openclassrooms.service_proxy"/>
</service>
```

#### Using specific cache provider

```xml
<!-- services.xml -->
<service id="a_service" class="AClass">
    <tag name="openclassrooms.service_proxy" cache="doctrine_cache.providers.a_cache_provider"/>
</service>
```

## Performance

### Autoloader
The usage of a proxy require a lot of I/O. See [Ocramius\ProxyManager Tunning for production](https://ocramius.github.io/ProxyManager/docs/tuning-for-production.html).  
It's possible to specify the environments where the proxy autoloader is used.

``` yaml

openclassrooms_service_proxy:
    production_environments: ['prod', 'stage'] # default : ['prod']
    
```

### Cache Warmup
The bundle uses the Symfony cache warmup to dump the proxies files. 

## Full configuration

``` yaml

openclassrooms_service_proxy:
    # the directory where the proxy are written
    cache_dir : "/a/path/to/the/cache/directory" # default: %kernel.cache_dir% 
    
    # the default cache provider (optional)
    default_cache: doctrine_cache.providers.a_cache_provider # default: null
    
    # the Symfony environments where the proxy autoloader is used
    production_environments: ['prod', 'stage'] # default : ['prod']
    
```
