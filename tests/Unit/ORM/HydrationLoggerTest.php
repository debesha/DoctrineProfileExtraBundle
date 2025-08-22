<?php

namespace Debesha\DoctrineProfileExtraBundle\Tests\Unit\ORM;

use Debesha\DoctrineProfileExtraBundle\ORM\HydrationLogger;
use PHPUnit\Framework\TestCase;

class HydrationLoggerTest extends TestCase
{
    private HydrationLogger $logger;

    protected function setUp(): void
    {
        $this->logger = new HydrationLogger();
    }

    public function test_constructor(): void
    {
        $this->assertInstanceOf(HydrationLogger::class, $this->logger);
        $this->assertEquals([], $this->logger->hydrations);
        $this->assertTrue($this->logger->enabled);
        $this->assertNull($this->logger->start);
        $this->assertEquals(0, $this->logger->currentHydration);
    }

    public function test_start_when_enabled(): void
    {
        $this->logger->enabled = true;

        $this->logger->start('object');

        $this->assertEquals(1, $this->logger->currentHydration);
        $this->assertNotNull($this->logger->start);
        $this->assertIsFloat($this->logger->start);
        $this->assertArrayHasKey(1, $this->logger->hydrations);
        $this->assertEquals('object', $this->logger->hydrations[1]['type']);
    }

    public function test_start_when_disabled(): void
    {
        $this->logger->enabled = false;
        $this->logger->start = 123.45;
        $this->logger->currentHydration = 5;

        $this->logger->start('array');

        // Should not change anything when disabled
        $this->assertEquals(5, $this->logger->currentHydration);
        $this->assertEquals(123.45, $this->logger->start);
        $this->assertArrayNotHasKey(6, $this->logger->hydrations);
    }

    public function test_start_increments_current_hydration(): void
    {
        $this->logger->enabled = true;

        $this->logger->start('object');
        $this->assertEquals(1, $this->logger->currentHydration);

        $this->logger->start('array');
        $this->assertEquals(2, $this->logger->currentHydration);

        $this->logger->start('scalar');
        $this->assertEquals(3, $this->logger->currentHydration);
    }

    public function test_start_sets_microtime(): void
    {
        $this->logger->enabled = true;

        $beforeStart = microtime(true);
        $this->logger->start('object');
        $afterStart = microtime(true);

        $this->assertGreaterThanOrEqual($beforeStart, $this->logger->start);
        $this->assertLessThanOrEqual($afterStart, $this->logger->start);
    }

    public function test_stop_when_enabled(): void
    {
        $this->logger->enabled = true;
        $this->logger->start('object');
        $this->logger->start = microtime(true) - 0.1; // Simulate 100ms ago

        $this->logger->stop(15, ['e' => 'TestEntity']);

        $this->assertArrayHasKey(1, $this->logger->hydrations);
        $this->assertArrayHasKey('executionMS', $this->logger->hydrations[1]);
        $this->assertArrayHasKey('resultNum', $this->logger->hydrations[1]);
        $this->assertArrayHasKey('aliasMap', $this->logger->hydrations[1]);

        $this->assertEquals(15, $this->logger->hydrations[1]['resultNum']);
        $this->assertEquals(['e' => 'TestEntity'], $this->logger->hydrations[1]['aliasMap']);

        // Execution time should be approximately 100ms
        $this->assertGreaterThan(0.09, $this->logger->hydrations[1]['executionMS']);
        $this->assertLessThan(0.11, $this->logger->hydrations[1]['executionMS']);
    }

    public function test_stop_when_disabled(): void
    {
        $this->logger->enabled = false;
        $this->logger->start('object');
        $this->logger->start = 123.45;

        $this->logger->stop(15, ['e' => 'TestEntity']);

        // Should not change anything when disabled
        $this->assertEquals(123.45, $this->logger->start);
        // When disabled, no hydration entry should be created
        $this->assertEmpty($this->logger->hydrations);
    }

    public function test_stop_without_start(): void
    {
        $this->logger->enabled = true;
        $this->logger->currentHydration = 1;

        // This should not cause an error
        $this->logger->stop(10, ['e' => 'TestEntity']);

        // Should still record the data even without a proper start
        $this->assertArrayHasKey(1, $this->logger->hydrations);
        $this->assertArrayHasKey('resultNum', $this->logger->hydrations[1]);
        $this->assertArrayHasKey('aliasMap', $this->logger->hydrations[1]);
    }

    public function test_multiple_hydrations(): void
    {
        $this->logger->enabled = true;

        // First hydration
        $this->logger->start('object');
        $this->logger->start = microtime(true) - 0.05;
        $this->logger->stop(10, ['e' => 'Entity1']);

        // Second hydration
        $this->logger->start('array');
        $this->logger->start = microtime(true) - 0.03;
        $this->logger->stop(5, ['e' => 'Entity2']);

        // Third hydration
        $this->logger->start('scalar');
        $this->logger->start = microtime(true) - 0.01;
        $this->logger->stop(1, ['e' => 'Entity3']);

        $this->assertEquals(3, $this->logger->currentHydration);
        $this->assertCount(3, $this->logger->hydrations);

        // Check first hydration
        $this->assertEquals('object', $this->logger->hydrations[1]['type']);
        $this->assertEquals(10, $this->logger->hydrations[1]['resultNum']);
        $this->assertEquals(['e' => 'Entity1'], $this->logger->hydrations[1]['aliasMap']);

        // Check second hydration
        $this->assertEquals('array', $this->logger->hydrations[2]['type']);
        $this->assertEquals(5, $this->logger->hydrations[2]['resultNum']);
        $this->assertEquals(['e' => 'Entity2'], $this->logger->hydrations[2]['aliasMap']);

        // Check third hydration
        $this->assertEquals('scalar', $this->logger->hydrations[3]['type']);
        $this->assertEquals(1, $this->logger->hydrations[3]['resultNum']);
        $this->assertEquals(['e' => 'Entity3'], $this->logger->hydrations[3]['aliasMap']);
    }

    public function test_enable_disable(): void
    {
        // Start with enabled
        $this->logger->enabled = true;
        $this->logger->start('object');
        $this->assertEquals(1, $this->logger->currentHydration);

        // Disable and start another
        $this->logger->enabled = false;
        $this->logger->start('array');
        $this->assertEquals(1, $this->logger->currentHydration); // Should not increment

        // Re-enable and start another
        $this->logger->enabled = true;
        $this->logger->start('scalar');
        $this->assertEquals(2, $this->logger->currentHydration); // Should increment again
    }

    public function test_stop_with_zero_results(): void
    {
        $this->logger->enabled = true;
        $this->logger->start('object');
        $this->logger->start = microtime(true) - 0.01;

        $this->logger->stop(0, []);

        $this->assertEquals(0, $this->logger->hydrations[1]['resultNum']);
        $this->assertEquals([], $this->logger->hydrations[1]['aliasMap']);
    }

    public function test_stop_with_empty_alias_map(): void
    {
        $this->logger->enabled = true;
        $this->logger->start('object');
        $this->logger->start = microtime(true) - 0.01;

        $this->logger->stop(5, []);

        $this->assertEquals(5, $this->logger->hydrations[1]['resultNum']);
        $this->assertEquals([], $this->logger->hydrations[1]['aliasMap']);
    }

    public function test_stop_with_complex_alias_map(): void
    {
        $this->logger->enabled = true;
        $this->logger->start('object');
        $this->logger->start = microtime(true) - 0.01;

        $complexAliasMap = [
            'e' => 'TestEntity',
            'c' => 'Category',
            'u' => 'User',
            'p' => 'Product',
        ];

        $this->logger->stop(25, $complexAliasMap);

        $this->assertEquals(25, $this->logger->hydrations[1]['resultNum']);
        $this->assertEquals($complexAliasMap, $this->logger->hydrations[1]['aliasMap']);
    }

    public function test_execution_time_accuracy(): void
    {
        $this->logger->enabled = true;

        // Start timing
        $this->logger->start('object');
        $startTime = $this->logger->start;

        // Simulate some work
        usleep(1000); // 1ms

        // Stop timing
        $this->logger->stop(1, ['e' => 'TestEntity']);

        $executionTime = $this->logger->hydrations[1]['executionMS'];

        // Execution time should be at least 1ms
        $this->assertGreaterThan(0.001, $executionTime);

        // And should be reasonable (not more than 10ms for this simple operation)
        $this->assertLessThan(0.01, $executionTime);
    }

    public function test_hydration_data_structure(): void
    {
        $this->logger->enabled = true;
        $this->logger->start('object');
        $this->logger->start = microtime(true) - 0.01;
        $this->logger->stop(10, ['e' => 'TestEntity']);

        $hydration = $this->logger->hydrations[1];

        // Check all required keys exist
        $this->assertArrayHasKey('type', $hydration);
        $this->assertArrayHasKey('executionMS', $hydration);
        $this->assertArrayHasKey('resultNum', $hydration);
        $this->assertArrayHasKey('aliasMap', $hydration);

        // Check data types
        $this->assertIsString($hydration['type']);
        $this->assertIsFloat($hydration['executionMS']);
        $this->assertIsInt($hydration['resultNum']);
        $this->assertIsArray($hydration['aliasMap']);

        // Check values
        $this->assertEquals('object', $hydration['type']);
        $this->assertEquals(10, $hydration['resultNum']);
        $this->assertEquals(['e' => 'TestEntity'], $hydration['aliasMap']);
    }
}
