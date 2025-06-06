<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\TestEntity;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route('/test')]
final class TestController
{
    public function __construct(
        #[Autowire(service: 'doctrine.orm.default_entity_manager')]
        private EntityManager $entityManager,
    ) {
    }

    public function __invoke(): JsonResponse
    {
        $entity1 = $this->entityManager->find(TestEntity::class, 1);
        $entity2 = $this->entityManager->find(TestEntity::class, 2);
        $entity3 = $this->entityManager->find(TestEntity::class, 3);

        return new JsonResponse([$entity1->foo, $entity2->foo, $entity3->foo]);
    }
}
