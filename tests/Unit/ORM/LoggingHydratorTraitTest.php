<?php

namespace Debesha\DoctrineProfileExtraBundle\Tests\Unit\ORM;

use Debesha\DoctrineProfileExtraBundle\ORM\LoggingHydratorTrait;
use PHPUnit\Framework\TestCase;

class LoggingHydratorTraitTest extends TestCase
{
    public function test_trait_exists(): void
    {
        $this->assertTrue(trait_exists(LoggingHydratorTrait::class));
    }

    public function test_trait_can_be_used(): void
    {
        // Test that the trait can be used in a class
        $testClass = new class {
            use LoggingHydratorTrait;
        };

        $this->assertIsObject($testClass);
        $this->assertTrue(method_exists($testClass, 'hydrateAll'));
    }

    public function test_trait_reflection(): void
    {
        $reflection = new \ReflectionClass(LoggingHydratorTrait::class);

        // Check that the trait has the expected method
        $this->assertTrue($reflection->hasMethod('hydrateAll'));

        // Check that the method is public
        $method = $reflection->getMethod('hydrateAll');
        $this->assertTrue($method->isPublic());
    }
}
