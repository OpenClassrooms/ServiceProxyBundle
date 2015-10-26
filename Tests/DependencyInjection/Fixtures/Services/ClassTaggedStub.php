<?php

namespace OpenClassrooms\Bundle\ServiceProxyBundle\Tests\DependencyInjection\Fixtures\Services;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class ClassTaggedStub
{
    /**
     * @return bool
     */
    public function aMethod()
    {
        return true;
    }
}
