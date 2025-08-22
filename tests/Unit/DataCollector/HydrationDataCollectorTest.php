<?php

namespace Debesha\DoctrineProfileExtraBundle\Tests\Unit\DataCollector;

use Debesha\DoctrineProfileExtraBundle\DataCollector\HydrationDataCollector;
use Debesha\DoctrineProfileExtraBundle\ORM\HydrationLogger;
use Debesha\DoctrineProfileExtraBundle\ORM\LoggingConfiguration;
use Debesha\DoctrineProfileExtraBundle\ORM\LoggingEntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HydrationDataCollectorTest extends TestCase
{
    private HydrationDataCollector $collector;
    private ManagerRegistry&MockObject $managerRegistry;
    private Request $request;
    private Response $response;

    protected function setUp(): void
    {
        $this->managerRegistry = $this->createMock(ManagerRegistry::class);
        $this->collector = new HydrationDataCollector($this->managerRegistry);
        $this->request = new Request();
        $this->response = new Response();
    }

    public function test_constructor(): void
    {
        $this->assertInstanceOf(HydrationDataCollector::class, $this->collector);
        $this->assertEquals([], $this->collector->getHydrations());
    }

    public function test_collect_with_logging_entity_managers(): void
    {
        // Create mock hydration logger with test data
        $hydrationLogger = new HydrationLogger();
        $hydrationLogger->hydrations = [
            [
                'type' => 'object',
                'executionMS' => 0.5,
                'resultNum' => 10,
                'aliasMap' => ['e' => 'TestEntity'],
            ],
            [
                'type' => 'array',
                'executionMS' => 0.3,
                'resultNum' => 5,
                'aliasMap' => ['e' => 'TestEntity'],
            ],
        ];

        // Create mock logging configuration
        $loggingConfiguration = $this->createMock(LoggingConfiguration::class);
        $loggingConfiguration->expects($this->any())
            ->method('getHydrationLogger')
            ->willReturn($hydrationLogger);

        // Create mock logging entity manager
        $loggingEntityManager = $this->createMock(LoggingEntityManager::class);
        $loggingEntityManager->expects($this->any())
            ->method('getConfiguration')
            ->willReturn($loggingConfiguration);

        // Create mock regular entity manager (should be ignored)
        $regularEntityManager = $this->createMock(ObjectManager::class);

        // Setup manager registry to return both types of managers
        $this->managerRegistry->expects($this->any())
            ->method('getManagers')
            ->willReturn([
                'logging' => $loggingEntityManager,
                'regular' => $regularEntityManager,
            ]);

        $this->collector->collect($this->request, $this->response);

        $hydrations = $this->collector->getHydrations();
        $this->assertCount(2, $hydrations);
        $this->assertEquals('object', $hydrations[0]['type']);
        $this->assertEquals('array', $hydrations[1]['type']);
    }

    public function test_collect_with_non_logging_entity_managers(): void
    {
        // Create mock regular entity manager
        $regularEntityManager = $this->createMock(ObjectManager::class);

        $this->managerRegistry->expects($this->any())
            ->method('getManagers')
            ->willReturn(['regular' => $regularEntityManager]);

        $this->collector->collect($this->request, $this->response);

        $this->assertEquals([], $this->collector->getHydrations());
    }

    public function test_collect_with_logging_entity_manager_but_non_logging_configuration(): void
    {
        // Create mock logging entity manager
        $loggingEntityManager = $this->createMock(LoggingEntityManager::class);

        // But with non-logging configuration
        $regularConfiguration = $this->createMock(\Doctrine\ORM\Configuration::class);
        $loggingEntityManager->expects($this->any())
            ->method('getConfiguration')
            ->willReturn($regularConfiguration);

        $this->managerRegistry->method('getManagers')
            ->willReturn(['logging' => $loggingEntityManager]);

        $this->collector->collect($this->request, $this->response);

        $this->assertEquals([], $this->collector->getHydrations());
    }

    public function test_collect_with_empty_hydrations(): void
    {
        // Create mock hydration logger with empty data
        $hydrationLogger = new HydrationLogger();
        $hydrationLogger->hydrations = [];

        $loggingConfiguration = $this->createMock(LoggingConfiguration::class);
        $loggingConfiguration->expects($this->any())
            ->method('getHydrationLogger')
            ->willReturn($hydrationLogger);

        $loggingEntityManager = $this->createMock(LoggingEntityManager::class);
        $loggingEntityManager->expects($this->any())
            ->method('getConfiguration')
            ->willReturn($loggingConfiguration);

        $this->managerRegistry->expects($this->any())
            ->method('getManagers')
            ->willReturn(['logging' => $loggingEntityManager]);

        $this->collector->collect($this->request, $this->response);

        $this->assertEquals([], $this->collector->getHydrations());
    }

    public function test_get_hydrations(): void
    {
        $testHydrations = [
            ['type' => 'test', 'executionMS' => 0.1],
        ];

        // Use reflection to set private data
        $reflection = new \ReflectionClass($this->collector);
        $dataProperty = $reflection->getProperty('data');
        $dataProperty->setAccessible(true);
        $dataProperty->setValue($this->collector, ['hydrations' => $testHydrations]);

        $this->assertEquals($testHydrations, $this->collector->getHydrations());
    }

    public function test_get_hydrations_count(): void
    {
        $testHydrations = [
            ['type' => 'test1'],
            ['type' => 'test2'],
            ['type' => 'test3'],
        ];

        // Use reflection to set private data
        $reflection = new \ReflectionClass($this->collector);
        $dataProperty = $reflection->getProperty('data');
        $dataProperty->setAccessible(true);
        $dataProperty->setValue($this->collector, ['hydrations' => $testHydrations]);

        $this->assertEquals(3, $this->collector->getHydrationsCount());
    }

    public function test_get_hydrations_count_with_empty_hydrations(): void
    {
        $this->assertEquals(0, $this->collector->getHydrationsCount());
    }

    public function test_get_time(): void
    {
        $testHydrations = [
            ['type' => 'test1', 'executionMS' => 0.5],
            ['type' => 'test2', 'executionMS' => 0.3],
            ['type' => 'test3', 'executionMS' => 0.2],
            ['type' => 'test4'], // Missing executionMS
        ];

        // Use reflection to set private data
        $reflection = new \ReflectionClass($this->collector);
        $dataProperty = $reflection->getProperty('data');
        $dataProperty->setAccessible(true);
        $dataProperty->setValue($this->collector, ['hydrations' => $testHydrations]);

        $this->assertEquals(1.0, $this->collector->getTime());
    }

    public function test_get_time_with_no_execution_time(): void
    {
        $testHydrations = [
            ['type' => 'test1'],
            ['type' => 'test2'],
        ];

        // Use reflection to set private data
        $reflection = new \ReflectionClass($this->collector);
        $dataProperty = $reflection->getProperty('data');
        $dataProperty->setAccessible(true);
        $dataProperty->setValue($this->collector, ['hydrations' => $testHydrations]);

        $this->assertEquals(0.0, $this->collector->getTime());
    }

    public function test_get_time_with_empty_hydrations(): void
    {
        $this->assertEquals(0.0, $this->collector->getTime());
    }

    public function test_get_name(): void
    {
        $this->assertEquals('hydrations', $this->collector->getName());
    }

    public function test_reset(): void
    {
        // First, add some test data
        $testHydrations = [
            ['type' => 'test1', 'executionMS' => 0.1],
            ['type' => 'test2', 'executionMS' => 0.2],
        ];

        // Use reflection to set private data
        $reflection = new \ReflectionClass($this->collector);
        $dataProperty = $reflection->getProperty('data');
        $dataProperty->setAccessible(true);
        $dataProperty->setValue($this->collector, ['hydrations' => $testHydrations]);

        // Verify data was set
        $this->assertCount(2, $this->collector->getHydrations());

        // Reset
        $this->collector->reset();

        // Verify data was reset
        $this->assertEquals([], $this->collector->getHydrations());
        $this->assertEquals(0, $this->collector->getHydrationsCount());
        $this->assertEquals(0.0, $this->collector->getTime());
    }

    public function test_collect_with_multiple_entity_managers(): void
    {
        // Create first hydration logger with test data
        $hydrationLogger1 = new HydrationLogger();
        $hydrationLogger1->hydrations = [
            ['type' => 'object', 'executionMS' => 0.5],
        ];

        $loggingConfiguration1 = $this->createMock(LoggingConfiguration::class);
        $loggingConfiguration1->expects($this->any())
            ->method('getHydrationLogger')
            ->willReturn($hydrationLogger1);

        $loggingEntityManager1 = $this->createMock(LoggingEntityManager::class);
        $loggingEntityManager1->expects($this->any())
            ->method('getConfiguration')
            ->willReturn($loggingConfiguration1);

        // Create second hydration logger with different test data
        $hydrationLogger2 = new HydrationLogger();
        $hydrationLogger2->hydrations = [
            ['type' => 'array', 'executionMS' => 0.3],
        ];

        $loggingConfiguration2 = $this->createMock(LoggingConfiguration::class);
        $loggingConfiguration2->expects($this->any())
            ->method('getHydrationLogger')
            ->willReturn($hydrationLogger2);

        $loggingEntityManager2 = $this->createMock(LoggingEntityManager::class);
        $loggingEntityManager2->expects($this->any())
            ->method('getConfiguration')
            ->willReturn($loggingConfiguration2);

        // Setup manager registry to return both managers
        $this->managerRegistry->expects($this->any())
            ->method('getManagers')
            ->willReturn([
                'em1' => $loggingEntityManager1,
                'em2' => $loggingEntityManager2,
            ]);

        $this->collector->collect($this->request, $this->response);

        $hydrations = $this->collector->getHydrations();
        $this->assertCount(2, $hydrations);
        $this->assertEquals('object', $hydrations[0]['type']);
        $this->assertEquals('array', $hydrations[1]['type']);
        $this->assertEquals(0.8, $this->collector->getTime());
    }
}
