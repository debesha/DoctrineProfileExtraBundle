<?php

namespace Debesha\DoctrineProfileExtraBundle\ORM;

/**
 * Collects information about performed hydrations
 *
 * This logger is used as a service to be injected to data collector.
 * Also it should be injected to Entity Configuration service, but it controlled by doctrine bundle.
 * So instead EntityManager is injected into logger and then inside constructor logger sets itself to
 * configuration of the EntityManager
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Dmytro Malyshenko <dmitry@malyshenko.com>
 */
class HydrationLogger
{
    /**
     * Executed hydrations
     */
    public array $hydrations = [];

    /**
     * If Debug Stack is enabled (log queries) or not.
     */
    public bool $enabled = true;

    public ?float $start = null;

    public int $currentHydration = 0;

    /**
     * Marks a hydration as started. Timing is started
     *
     * @param string $type type of hydration
     */
    public function start(string $type): void
    {
        if ($this->enabled) {
            $this->start = microtime(true);

            $this->hydrations[++$this->currentHydration]['type'] = $type;
        }
    }

    /**
     * Marks a hydration as stopped. Number of hydrated entities and alias map is
     * passed to method.
     */
    public function stop(int $resultNum, array $aliasMap): void
    {
        if ($this->enabled) {
            $this->hydrations[$this->currentHydration]['executionMS'] = microtime(true) - $this->start;
            $this->hydrations[$this->currentHydration]['resultNum'] = $resultNum;
            $this->hydrations[$this->currentHydration]['aliasMap'] = $aliasMap;
        }
    }
}
