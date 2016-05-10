<?php

namespace Debesha\DoctrineProfileExtraBundle\ORM;

/**
 * Add methods to operate with hydration logger
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Dmytro Malyshenko <dmitry@malyshenko.com>
 */
class LoggingConfiguration extends \Doctrine\ORM\Configuration
{
    public function __construct()
    {
        $hydrationLogger = new HydrationLogger();
        $this->setHydrationLogger($hydrationLogger);
    }
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
     */
    public function setHydrationLogger(HydrationLogger $logger)
    {
        $this->_attributes['hydrationLogger'] = $logger;
    }
}
