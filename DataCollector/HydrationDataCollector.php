<?php
/**
 * Data collector takes information about performed hydrations from
 * injected hydrationLogger
 *
 * Author: Dmytry Malyshenko (dmitry@malyshenko.com)
 * Date: 16.07.2015
 * Time: 19:11
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Debesha\DoctrineProfileExtraBundle\DataCollector;

use Debesha\DoctrineProfileExtraBundle\ORM\HydrationLogger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

class HydrationDataCollector extends DataCollector
{
    private HydrationLogger $hydrationLogger;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->hydrationLogger = $manager->getConfiguration()->getHydrationLogger();
    }

    public function collect(Request $request, Response $response, \Throwable $exception = null): void
    {
        $this->data['hydrations'] = $this->hydrationLogger->hydrations;
    }

    public function getHydrations()
    {
        return $this->data['hydrations'];
    }

    public function getHydrationsCount(): int
    {
        return count($this->data['hydrations']);
    }

    public function getTime(): int
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
        $this->data = [];
        $this->hydrationLogger->hydrations = [];
        $this->hydrationLogger->currentHydration = 0;
    }
}
