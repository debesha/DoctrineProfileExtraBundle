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
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

class HydrationDataCollector extends DataCollector
{
    /**
     * @var HydrationLogger
     */
    private $hydrationLogger = [];

    public function __construct(EntityManager $manager)
    {
        $this->hydrationLogger = $manager->getConfiguration()->getHydrationLogger();
    }

    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data['hydrations'] = $this->hydrationLogger->hydrations;
    }

    public function getHydrations()
    {
        return $this->data['hydrations'];
    }

    public function getHydrationsCount()
    {
        return count($this->data['hydrations']);
    }

    public function getTime()
    {
        $time = 0;
        foreach ($this->data['hydrations'] as $hydration) {
            if (isset($hydration['executionMS'])) {
                $time += $hydration['executionMS'];
            }
        }

        return $time;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'hydrations';
    }
}
