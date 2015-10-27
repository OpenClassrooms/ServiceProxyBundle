<?php

namespace OpenClassrooms\Bundle\ServiceProxyBundle\Tests\Fixtures\Services;

use OpenClassrooms\ServiceProxy\Annotations\Cache;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class CacheClassStub
{
    /**
     * @Cache
     */
    public function aMethod()
    {
        return true;
    }
}
