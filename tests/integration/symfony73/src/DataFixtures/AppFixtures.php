<?php

namespace App\DataFixtures;

use App\Entity\TestEntity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $entity1 = new TestEntity('bar');
        $manager->persist($entity1);
        $entity2 = new TestEntity('baz');
        $manager->persist($entity2);
        $entity3 = new TestEntity('qux');
        $manager->persist($entity3);

        $manager->flush();
    }
}
