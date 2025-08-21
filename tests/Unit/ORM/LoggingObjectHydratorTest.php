<?php

namespace Debesha\DoctrineProfileExtraBundle\Tests\Unit\ORM;

use Debesha\DoctrineProfileExtraBundle\ORM\LoggingObjectHydrator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Internal\Hydration\ObjectHydrator;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class LoggingObjectHydratorTest extends TestCase
{
    private LoggingObjectHydrator $hydrator;

    protected function setUp(): void
    {
        /** @var EntityManagerInterface&MockObject $entityManager */
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $this->hydrator = new LoggingObjectHydrator($entityManager);
    }

    public function testExtendsObjectHydrator(): void
    {
        $this->assertInstanceOf(ObjectHydrator::class, $this->hydrator);
    }

    public function testUsesLoggingHydratorTrait(): void
    {
        $reflection = new \ReflectionClass($this->hydrator);
        $traits = $reflection->getTraitNames();
        
        $this->assertContains(
            'Debesha\DoctrineProfileExtraBundle\ORM\LoggingHydratorTrait',
            $traits,
            'LoggingObjectHydrator should use LoggingHydratorTrait'
        );
    }

    public function testClassStructure(): void
    {
        $reflection = new \ReflectionClass($this->hydrator);
        
        // Check that the class has the expected methods
        $this->assertTrue($reflection->hasMethod('hydrateAll'));
        
        // Check that the method is public
        $method = $reflection->getMethod('hydrateAll');
        $this->assertTrue($method->isPublic());
    }

    public function testInheritanceChain(): void
    {
        $this->assertInstanceOf(LoggingObjectHydrator::class, $this->hydrator);
        $this->assertInstanceOf(ObjectHydrator::class, $this->hydrator);
    }
}
