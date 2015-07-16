<?php

namespace Debesha\DoctrineProfileExtraBundle\ORM;

/**
 * Add methods to operate with hydration logger
 *
 * @author Dmytro Malyshenko <dmitry@malyshenko.com>
 */

class LoggingConfiguration extends \Doctrine\ORM\Configuration
{

    /**
     * Gets the hydration logger.
     *
     * @return HydrationLogger
     */
    public function getHydrationLogger()
    {
        return isset($this->_attributes['hydrationLogger'])
            ? $this->_attributes['hydrationLogger']
            : null;
    }

    /**
     * Sets the hydration logger.
     *
     * @param HydrationLogger $ns
     *
     * @return void
     */
    public function setHydrationLogger(HydrationLogger $logger)
    {
        $this->_attributes['hydrationLogger'] = $logger;
    }
}
