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

    public function getHydrationLogger(): ?HydrationLogger
    {
        return $this->_attributes['hydrationLogger'] ?? null;
    }

    public function setHydrationLogger(HydrationLogger $logger): void
    {
        $this->_attributes['hydrationLogger'] = $logger;
    }
}
