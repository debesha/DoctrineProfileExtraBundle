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

    public function test_extends_array_hydrator(): void
    {
        $this->assertInstanceOf(ArrayHydrator::class, $this->hydrator);
    }

    public function test_uses_logging_hydrator_trait(): void
    {
        $reflection = new \ReflectionClass($this->hydrator);
        $traits = $reflection->getTraitNames();

        $this->assertContains(
            'Debesha\DoctrineProfileExtraBundle\ORM\LoggingHydratorTrait',
            $traits,
            'LoggingArrayHydrator should use LoggingHydratorTrait'
        );
    }

    public function test_class_structure(): void
    {
        $reflection = new \ReflectionClass($this->hydrator);

        // Check that the class has the expected methods
        $this->assertTrue($reflection->hasMethod('hydrateAll'));

        // Check that the method is public
        $method = $reflection->getMethod('hydrateAll');
        $this->assertTrue($method->isPublic());
    }

    public function test_inheritance_chain(): void
    {
        $this->assertInstanceOf(LoggingArrayHydrator::class, $this->hydrator);
        $this->assertInstanceOf(ArrayHydrator::class, $this->hydrator);
    }
}
