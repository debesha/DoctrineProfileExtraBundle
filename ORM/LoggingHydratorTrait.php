<?php

/**
 * Redefines method hydrateAll for all hydrators.
 * In new method start() and end() of logger are called, if logger is set.
 * Between these calls parent' method is called.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Author: Dmytry Malyshenko (dmitry@malyshenko.com)
 * Date: 16.07.2015
 */

namespace Debesha\DoctrineProfileExtraBundle\ORM;

use Doctrine\DBAL\Result;
use Doctrine\ORM\Internal\Hydration\AbstractHydrator;
use Doctrine\ORM\Query\ResultSetMapping;

if (property_exists(AbstractHydrator::class, '_em')) {
    // ORM 2
    /**
     * @phpstan-require-extends \Doctrine\ORM\Internal\Hydration\AbstractHydrator
     *
     * @property \Doctrine\ORM\EntityManagerInterface $_em
     */
    trait LoggingHydratorTrait
    {
        /**
         * Hydrates all rows returned by the passed statement instance at once.
         *
         * @param Result               $stmt
         * @param ResultSetMapping     $resultSetMapping
         * @param array<string, mixed> $hints
         *
         * @phpstan-return mixed
         */
        public function hydrateAll(/* Result */ $stmt, /* ResultSetMapping */ $resultSetMapping, /* array */ $hints = [])/* : Countable|array */
        {
            // @phpstan-ignore-next-line Access through magic property on ORM2 hydrator
            $logger = $this->_em?->getConfiguration()?->getHydrationLogger();
            if ($logger) {
                $shortName = (new \ReflectionClass($this))->getShortName();
                $type = preg_replace('/^Logging/', '', $shortName) ?: $shortName;
                $logger->start($type);
            }

            // @phpstan-ignore-next-line parent::hydrateAll exists in real hydrator subclasses
            $result = parent::hydrateAll($stmt, $resultSetMapping, $hints);

            if (isset($logger)) {
                if (is_countable($result)) {
                    $logger->stop(\count($result), $resultSetMapping->getAliasMap());
                }
            }

            return $result;
        }
    }
} else {
    // ORM 3
    /**
     * @phpstan-require-extends \Doctrine\ORM\Internal\Hydration\AbstractHydrator
     *
     * @property \Doctrine\ORM\EntityManagerInterface $em
     */
    trait LoggingHydratorTrait
    {
        /**
         * Hydrates all rows returned by the passed statement instance at once.
         *
         * @psalm-param array<string, string> $hints
         *
         * @phpstan-return mixed
         */
        public function hydrateAll(Result $stmt, ResultSetMapping $resultSetMapping, array $hints = []): mixed
        {
            // For ORM 2.0 and 3.0 compatibility
            $logger = $this->em->getConfiguration()->getHydrationLogger();
            if ($logger) {
                $shortName = (new \ReflectionClass($this))->getShortName();
                $type = preg_replace('/^Logging/', '', $shortName) ?: $shortName;
                $logger->start($type);
            }

            // @phpstan-ignore-next-line parent::hydrateAll exists in real hydrator subclasses
            $result = parent::hydrateAll($stmt, $resultSetMapping, $hints);

            if (isset($logger)) {
                if (is_countable($result)) {
                    $logger->stop(\count($result), $resultSetMapping->getAliasMap());
                }
            }

            return $result;
        }
    }
}
