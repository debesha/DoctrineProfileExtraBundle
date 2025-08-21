<?php

namespace Debesha\DoctrineProfileExtraBundle\Tests\Unit\ORM;

use Debesha\DoctrineProfileExtraBundle\ORM\LoggingSimpleObjectHydrator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Internal\Hydration\SimpleObjectHydrator;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class LoggingSimpleObjectHydratorTest extends TestCase
{
    private LoggingSimpleObjectHydrator $hydrator;

    protected function setUp(): void
    {
        /** @var EntityManagerInterface&MockObject $entityManager */
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $this->hydrator = new LoggingSimpleObjectHydrator($entityManager);
    }

    public function testExtendsSimpleObjectHydrator(): void
    {
        $this->assertInstanceOf(SimpleObjectHydrator::class, $this->hydrator);
    }

    public function testUsesLoggingHydratorTrait(): void
    {
        $reflection = new \ReflectionClass($this->hydrator);
        $traits = $reflection->getTraitNames();
        
        $this->assertContains(
            'Debesha\DoctrineProfileExtraBundle\ORM\LoggingHydratorTrait',
            $traits,
            'LoggingSimpleObjectHydrator should use LoggingHydratorTrait'
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
        $this->assertInstanceOf(LoggingSimpleObjectHydrator::class, $this->hydrator);
        $this->assertInstanceOf(SimpleObjectHydrator::class, $this->hydrator);
    }
}
