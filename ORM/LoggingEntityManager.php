<?php
/**
 * Author: Dmytry Malyshenko (dmitry@malyshenko.com)
 * Date: 16.07.2015
 * Time: 12:52
 */

namespace Debesha\DoctrineProfileExtraBundle\ORM;

use Doctrine\Common\EventManager;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query;
use Doctrine\DBAL\Connection;

class LoggingEntityManager extends EntityManager {

    /**
     * {@inheritDoc}
     */
    public function newHydrator($hydrationMode)
    {

        switch ($hydrationMode) {
            case Query::HYDRATE_OBJECT:
                return new LoggingObjectHydrator($this);

            case Query::HYDRATE_ARRAY:
                return new LoggingArrayHydrator($this);

            case Query::HYDRATE_SCALAR:
                return new LoggingScalarHydrator($this);

            case Query::HYDRATE_SINGLE_SCALAR:
                return new LoggingSingleScalarHydrator($this);

            case Query::HYDRATE_SIMPLEOBJECT:
                return new LoggingSimpleObjectHydrator($this);
            default:
                return parent::newHydrator($hydrationMode);
        }
    }

    /**
     * @return LoggingConfiguration
     */
    public function getConfiguration()
    {
        return parent::getConfiguration();
    }

    /**
     * {@inheritDoc}
     */

    public static function create($conn, Configuration $config, EventManager $eventManager = null)
    {
        if ( ! $config->getMetadataDriverImpl()) {
            throw ORMException::missingMappingDriverImpl();
        }

        switch (true) {
            case (is_array($conn)):
                $conn = \Doctrine\DBAL\DriverManager::getConnection(
                    $conn, $config, ($eventManager ?: new EventManager())
                );
                break;

            case ($conn instanceof Connection):
                if ($eventManager !== null && $conn->getEventManager() !== $eventManager) {
                    throw ORMException::mismatchedEventManager();
                }
                break;

            default:
                throw new \InvalidArgumentException("Invalid argument: " . $conn);
        }

        return new LoggingEntityManager($conn, $config, $conn->getEventManager());
    }
}