<?php

    /**
     * Redefines method hydrateAll for all hydrators.
     * In new method start() and end() of logger are called, if logger is set.
     * Between these calls parent' method is called
     *
     * For the full copyright and license information, please view the LICENSE
     * file that was distributed with this source code.
     *
     * Author: Dmytry Malyshenko (dmitry@malyshenko.com)
     * Date: 16.07.2015
     */

    namespace Debesha\DoctrineProfileExtraBundle\ORM;

    trait LoggingHydratorTrait {

        /**
         * Hydrates all rows returned by the passed statement instance at once.
         *
         * @param \Doctrine\DBAL\Driver\Statement      $stmt
         * @param \Doctrine\ORM\Query\ResultSetMapping $resultSetMapping
         * @param array                                $hints
         *
         * @return array
         */

        public function hydrateAll($stmt, $resultSetMapping, array $hints = array ()) {
            if ($logger = $this->_em->getConfiguration()->getHydrationLogger())
                $logger->start();

            $result = parent::hydrateAll($stmt, $resultSetMapping, $hints);

            if ($logger) {

                $logger->stop(sizeof($result), $resultSetMapping->getAliasMap());
            }

            return $result;
        }
    }
