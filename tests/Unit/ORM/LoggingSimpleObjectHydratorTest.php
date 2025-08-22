<?php

namespace Debesha\DoctrineProfileExtraBundle\Tests\Unit\ORM;

use Debesha\DoctrineProfileExtraBundle\ORM\LoggingSimpleObjectHydrator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Internal\Hydration\SimpleObjectHydrator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class LoggingSimpleObjectHydratorTest extends TestCase
{
    private LoggingSimpleObjectHydrator $hydrator;

    protected function setUp(): void
    {
        /** @var EntityManagerInterface&MockObject $entityManager */
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $this->hydrator = new LoggingSimpleObjectHydrator($entityManager);
    }

    public function test_extends_simple_object_hydrator(): void
    {
        $this->assertInstanceOf(SimpleObjectHydrator::class, $this->hydrator);
    }

    public function test_uses_logging_hydrator_trait(): void
    {
        $reflection = new \ReflectionClass($this->hydrator);
        $traits = $reflection->getTraitNames();

        $this->assertContains(
            'Debesha\DoctrineProfileExtraBundle\ORM\LoggingHydratorTrait',
            $traits,
            'LoggingSimpleObjectHydrator should use LoggingHydratorTrait'
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
        $this->assertInstanceOf(LoggingSimpleObjectHydrator::class, $this->hydrator);
        $this->assertInstanceOf(SimpleObjectHydrator::class, $this->hydrator);
    }
}
