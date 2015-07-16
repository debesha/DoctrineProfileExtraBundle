<?php
/**
 * Author: Dmytry Malyshenko (dmitry@malyshenko.com)
 * Date: 16.07.2015
 * Time: 19:11
 */

namespace Debesha\DoctrineProfileExtraBundle\DataCollector;

use Debesha\DoctrineProfileExtraBundle\ORM\HydrationLogger;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

class HydrationDataCollector extends DataCollector {
    /**
     * @var HydrationLogger
     */
    private $hydrationLogger = array();

    public function __construct(HydrationLogger $logger) {

        $this->hydrationLogger = $logger;
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
           $time += $hydration['executionMS'];
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