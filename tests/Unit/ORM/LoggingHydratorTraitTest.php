<?php

namespace Debesha\DoctrineProfileExtraBundle\Tests\Unit\ORM;

use Debesha\DoctrineProfileExtraBundle\ORM\LoggingHydratorTrait;
use PHPUnit\Framework\TestCase;

class LoggingHydratorTraitTest extends TestCase
{
    public function testTraitExists(): void
    {
        $this->assertTrue(trait_exists(LoggingHydratorTrait::class));
    }

    public function testTraitCanBeUsed(): void
    {
        // Test that the trait can be used in a class
        $testClass = new class() {
            use LoggingHydratorTrait;
        };

        $this->assertIsObject($testClass);
        $this->assertTrue(method_exists($testClass, 'hydrateAll'));
    }

    public function testTraitReflection(): void
    {
        $reflection = new \ReflectionClass(LoggingHydratorTrait::class);
        
        // Check that the trait has the expected method
        $this->assertTrue($reflection->hasMethod('hydrateAll'));
        
        // Check that the method is public
        $method = $reflection->getMethod('hydrateAll');
        $this->assertTrue($method->isPublic());
    }
}
