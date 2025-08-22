<?php

/**
 * Extends Doctrine Entity Manager.
 * While creating hydrations returns extended hydrations, where methods hydrateAll() are
 * redefined. Inside hydrateAll() hydration logger is called to log performance of hydrations.
 *
 * Also factory method create() is redefined in order to get EntityManager of this class but not a parent one.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Author: Dmytry Malyshenko (dmitry@malyshenko.com)
 * Date: 16.07.2015
 * Time: 12:52
 */

namespace Debesha\DoctrineProfileExtraBundle\ORM;

use Doctrine\Common\EventManager;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Internal\Hydration\AbstractHydrator;
use Doctrine\ORM\ORMException;

class LoggingEntityManager extends EntityManager
{
    public function newHydrator($hydrationMode): AbstractHydrator
    {
        return match ($hydrationMode) {
            AbstractQuery::HYDRATE_OBJECT => new LoggingObjectHydrator($this),
            AbstractQuery::HYDRATE_ARRAY => new LoggingArrayHydrator($this),
            AbstractQuery::HYDRATE_SCALAR => new LoggingScalarHydrator($this),
            AbstractQuery::HYDRATE_SINGLE_SCALAR => new LoggingSingleScalarHydrator($this),
            AbstractQuery::HYDRATE_SIMPLEOBJECT => new LoggingSimpleObjectHydrator($this),
            default => parent::newHydrator($hydrationMode),
        };
    }

    /**
     * @throws Exception|ORMException
     */
    public static function create($connection, Configuration $config, ?EventManager $eventManager = null): EntityManager
    {
        if (!$config->getMetadataDriverImpl()) {
            throw ORMException::missingMappingDriverImpl();
        }

        switch (true) {
            case \is_array($connection):
                $connection = \Doctrine\DBAL\DriverManager::getConnection(
                    $connection, $config, $eventManager ?: new EventManager()
                );
                break;

            case $connection instanceof Connection:
                if (null !== $eventManager && $connection->getEventManager() !== $eventManager) {
                    throw ORMException::mismatchedEventManager();
                }
                break;

            default:
                throw new \InvalidArgumentException('Invalid argument');
        }

        return new self($connection, $config, $connection->getEventManager());
    }
}
