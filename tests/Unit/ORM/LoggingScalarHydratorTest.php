<?php

namespace Debesha\DoctrineProfileExtraBundle\Tests\Unit\ORM;

use Debesha\DoctrineProfileExtraBundle\ORM\LoggingScalarHydrator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Internal\Hydration\ScalarHydrator;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class LoggingScalarHydratorTest extends TestCase
{
    private LoggingScalarHydrator $hydrator;

    protected function setUp(): void
    {
        /** @var EntityManagerInterface&MockObject $entityManager */
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $this->hydrator = new LoggingScalarHydrator($entityManager);
    }

    public function testExtendsScalarHydrator(): void
    {
        $this->assertInstanceOf(ScalarHydrator::class, $this->hydrator);
    }

    public function testUsesLoggingHydratorTrait(): void
    {
        $reflection = new \ReflectionClass($this->hydrator);
        $traits = $reflection->getTraitNames();
        
        $this->assertContains(
            'Debesha\DoctrineProfileExtraBundle\ORM\LoggingHydratorTrait',
            $traits,
            'LoggingScalarHydrator should use LoggingHydratorTrait'
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
        $this->assertInstanceOf(LoggingScalarHydrator::class, $this->hydrator);
        $this->assertInstanceOf(ScalarHydrator::class, $this->hydrator);
    }
}
