<?php

/**
 * Data collector takes information about performed hydrations from
 * injected hydrationLogger.
 *
 * Author: Dmytry Malyshenko (dmitry@malyshenko.com)
 * Date: 16.07.2015
 * Time: 19:11
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Debesha\DoctrineProfileExtraBundle\DataCollector;

use Debesha\DoctrineProfileExtraBundle\ORM\LoggingConfiguration;
use Debesha\DoctrineProfileExtraBundle\ORM\LoggingEntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

class HydrationDataCollector extends DataCollector
{
    public function __construct(
        private ManagerRegistry $managerRegistry,
    ) {
        $this->data['hydrations'] = [];
    }

    public function collect(Request $request, Response $response, ?\Throwable $exception = null): void
    {
        $hydrationsPerEntityManager = array_map(
            static function (ObjectManager $manager): array {
                if (!$manager instanceof LoggingEntityManager) {
                    return [];
                }

                $configuration = $manager->getConfiguration();
                if (!$configuration instanceof LoggingConfiguration) {
                    return [];
                }

                $logger = $configuration->getHydrationLogger();
                if (null === $logger) {
                    return [];
                }

                return $logger->hydrations;
            },
            $this->managerRegistry->getManagers(),
        );

        $this->data['hydrations'] = array_merge(...array_values($hydrationsPerEntityManager));
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getHydrations(): array
    {
        return $this->data['hydrations'];
    }

    public function getHydrationsCount(): int
    {
        return \count($this->data['hydrations']);
    }

    public function getTime(): float
    {
        $time = 0;
        foreach ($this->data['hydrations'] as $hydration) {
            if (isset($hydration['executionMS'])) {
                $time += $hydration['executionMS'];
            }
        }

        return $time;
    }

    public function getName(): string
    {
        return 'hydrations';
    }

    public function reset(): void
    {
        $this->data = ['hydrations' => []];
    }
}
