<?php

namespace Debesha\DoctrineProfileExtraBundle\Tests\Unit\ORM;

use Debesha\DoctrineProfileExtraBundle\ORM\LoggingArrayHydrator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Internal\Hydration\ArrayHydrator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class LoggingArrayHydratorTest extends TestCase
{
    private LoggingArrayHydrator $hydrator;

    protected function setUp(): void
    {
        /** @var EntityManagerInterface&MockObject $entityManager */
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $this->hydrator = new LoggingArrayHydrator($entityManager);
    }

    public function testExtendsArrayHydrator(): void
    {
        $this->assertInstanceOf(ArrayHydrator::class, $this->hydrator);
    }

    public function testUsesLoggingHydratorTrait(): void
    {
        $reflection = new \ReflectionClass($this->hydrator);
        $traits = $reflection->getTraitNames();

        $this->assertContains(
            'Debesha\DoctrineProfileExtraBundle\ORM\LoggingHydratorTrait',
            $traits,
            'LoggingArrayHydrator should use LoggingHydratorTrait'
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
        $this->assertInstanceOf(LoggingArrayHydrator::class, $this->hydrator);
        $this->assertInstanceOf(ArrayHydrator::class, $this->hydrator);
    }
}
