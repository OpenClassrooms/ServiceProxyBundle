<?php

namespace OpenClassrooms\Bundle\ServiceProxyBundle\Tests\Fixtures\Services;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class ClassTaggedStub
{
    /**
     * @var ClassStub
     */
    private $anInjectedParameter;

    /**
     * @return bool
     */
    public function aMethod()
    {
        return $this->anInjectedParameter;
    }

    public function setAnInjectedParameter($anInjectedParameter)
    {
        $this->anInjectedParameter = $anInjectedParameter;
    }
}
