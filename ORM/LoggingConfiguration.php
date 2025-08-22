<?php

namespace Debesha\DoctrineProfileExtraBundle\ORM;

/**
 * Add methods to operate with hydration logger.
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

    public function getHydrationLogger(): ?HydrationLogger
    {
        if (property_exists($this, '_attributes')) {
            // ORM 2
            return $this->_attributes['hydrationLogger'] ?? null;
        } else {
            // ORM 3
            return $this->attributes['hydrationLogger'] ?? null;
        }
    }

    public function setHydrationLogger(HydrationLogger $logger): void
    {
        if (property_exists($this, '_attributes')) {
            // ORM 2
            $this->_attributes['hydrationLogger'] = $logger;
        } else {
            // ORM 3
            $this->attributes['hydrationLogger'] = $logger;
        }
    }
}
