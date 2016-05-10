<?php

namespace Debesha\DoctrineProfileExtraBundle\ORM;

use Doctrine\ORM\EntityManager;

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
     *
     * @var array
     */
    public $hydrations = [];

    /**
     * If Debug Stack is enabled (log queries) or not.
     *
     * @var bool
     */
    public $enabled = true;

    /**
     * @var float|null
     */
    public $start = null;

    /**
     * @var int
     */
    public $currentHydration = 0;

    /**
     * Marks a hydration as started. Timing is started
     *
     * @param string $type type of hydration
     */
    public function start($type)
    {
        if ($this->enabled) {
            $this->start = microtime(true);

            $this->hydrations[++$this->currentHydration]['type'] = $type;
        }
    }

    /**
     * Marks a hydration as stopped. Number of hydrated entities and alias map is
     * passed to method.
     *
     * @param int   $resultNum
     * @param array $aliasMap
     */
    public function stop($resultNum, $aliasMap)
    {
        if ($this->enabled) {
            $this->hydrations[$this->currentHydration]['executionMS'] = microtime(true) - $this->start;
            $this->hydrations[$this->currentHydration]['resultNum'] = $resultNum;
            $this->hydrations[$this->currentHydration]['aliasMap'] = $aliasMap;
        }
    }
}
