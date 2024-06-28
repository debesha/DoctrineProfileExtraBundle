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

use Countable;
use Doctrine\DBAL\Result;
use Doctrine\ORM\Internal\Hydration\ArrayHydrator;
use Doctrine\ORM\Internal\Hydration\ObjectHydrator;
use Doctrine\ORM\Internal\Hydration\ScalarHydrator;
use Doctrine\ORM\Internal\Hydration\SimpleObjectHydrator;
use Doctrine\ORM\Internal\Hydration\SingleScalarHydrator;
use Doctrine\ORM\Query\ResultSetMapping;

trait LoggingHydratorTrait
{
    /**
     * Hydrates all rows returned by the passed statement instance at once.
     *
     * @param Result|ResultStatement $stmt
     * @param ResultSetMapping       $resultSetMapping
     * @psalm-param array<string, string> $hints
     *
     * @return mixed[]
     */
    public function hydrateAll(Result $stmt, ResultSetMapping $resultSetMapping, array $hints = []): mixed
    {
        // For ORM 2.0 and 3.0 compatibility
        $entityManager = isset($this->em) ? $this->em : $this->_em;

        if ($logger = $this->em->getConfiguration()->getHydrationLogger()) {
            $type = null;

            if ($this instanceof ObjectHydrator) {
                $type = 'ObjectHydrator';
            } elseif ($this instanceof ArrayHydrator) {
                $type = 'ArrayHydrator';
            } elseif ($this instanceof ScalarHydrator) {
                $type = 'ScalarHydrator';
            } elseif ($this instanceof SimpleObjectHydrator) {
                $type = 'SimpleObjectHydrator';
            } elseif ($this instanceof SingleScalarHydrator) {
                $type = 'SingleScalarHydrator';
            }

            $logger->start($type);
        }

        $result = parent::hydrateAll($stmt, $resultSetMapping, $hints);

        if ($logger) {
            if (is_countable($result)) {
                $logger->stop(count($result), $resultSetMapping->getAliasMap());
            }
        }

        return $result;
    }
}
