<?php

declare(strict_types=1);

namespace App\Tests;

use Debesha\DoctrineProfileExtraBundle\DataCollector\HydrationDataCollector;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class TestControllerTest extends WebTestCase
{
    public function testController(): void
    {
        $client = static::createClient();
        $client->enableProfiler();
        $client->request('GET', '/test');

        $this->assertResponseIsSuccessful();

        $dataCollector = self::getContainer()->get('debesha.doctrine_extra_profiler.data_collector.public');
        \assert($dataCollector instanceof HydrationDataCollector);

        self::assertEquals(3, $dataCollector->getHydrationsCount());
    }
}