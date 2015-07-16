<?php

    namespace Debesha\DoctrineProfileExtraBundle\ORM;
    use Doctrine\ORM\EntityManager;

    /**
     * Collects information about performed hydrations
     *
     * @author Dmytro Malyshenko <dmitry@malyshenko.com>
     *
     */

    class HydrationLogger
    {
        /**
         * Executed hydrations
         *
         * @var array
         */
        public $hydrations = array();

        /**
         * If Debug Stack is enabled (log queries) or not.
         *
         * @var boolean
         */
        public $enabled = true;

        /**
         * @var float|null
         */
        public $start = null;

        /**
         * @var integer
         */
        public $currentHydration = 0;

        /**
         */

        public function __construct(LoggingEntityManager $entityManager) {

            $entityManager->getConfiguration()->setHydrationLogger($this);
        }

        public function start()
        {
            if ($this->enabled) {
                $this->start = microtime(true);
            }

            $this->currentHydration++;
        }

        /**
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