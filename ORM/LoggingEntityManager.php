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
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Internal\Hydration\AbstractHydrator;

/** @phpstan-ignore-next-line Extending Doctrine EntityManager intentionally for logging */
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
     * @param array<string, mixed>|Connection $connection
     *
     * @throws Exception
     */
    public static function create($connection, Configuration $config, ?EventManager $eventManager = null): EntityManager
    {
        if (!$config->getMetadataDriverImpl()) {
            throw new \InvalidArgumentException('Missing mapping driver implementation');
        }

        switch (true) {
            case \is_array($connection):
                $connection = DriverManager::getConnection(
                    $connection,
                    $config
                );
                break;

            case $connection instanceof Connection:
                if (null !== $eventManager) {
                    $connectionEventManager = method_exists($connection, 'getEventManager') ? $connection->getEventManager() : null;
                    if (null !== $connectionEventManager && $connectionEventManager !== $eventManager) {
                        throw new \InvalidArgumentException('Mismatched event manager');
                    }
                }
                break;

            default:
                throw new \InvalidArgumentException('Invalid argument');
        }

        $em = $eventManager;
        if (null === $em && method_exists($connection, 'getEventManager')) {
            $em = $connection->getEventManager();
        }

        return new self($connection, $config, $em ?: new EventManager());
    }
}
