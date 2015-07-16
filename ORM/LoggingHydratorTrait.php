<?php

namespace Debesha\DoctrineProfileExtraBundle\ORM;

trait LoggingHydratorTrait
{

    /**
     * Hydrates all rows returned by the passed statement instance at once.
     *
     * @param \Doctrine\DBAL\Driver\Statement $stmt
     * @param \Doctrine\ORM\Query\ResultSetMapping $resultSetMapping
     * @param array  $hints
     *
     * @return array
     */

    public function hydrateAll($stmt, $resultSetMapping, array $hints = array())
    {
        if ($logger = $this->_em->getConfiguration()->getHydrationLogger())
            $logger->start();

        $result = parent::hydrateAll($stmt, $resultSetMapping, $hints);

        if ($logger) {

            $logger->stop(sizeof($result), $resultSetMapping->getAliasMap());
        }

        return $result;
    }
 }
