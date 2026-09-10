<?php

namespace Debesha\DoctrineProfileExtraBundle\DependencyInjection\Compiler;

use Debesha\DoctrineProfileExtraBundle\ORM\LoggingConfiguration;
use Debesha\DoctrineProfileExtraBundle\ORM\LoggingEntityManager;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * DoctrineBundle 3 hardcodes the entity manager/configuration classes in its
 * service definitions instead of reading them from the
 * doctrine.orm.entity_manager.class / doctrine.orm.configuration.class
 * parameters (as DoctrineBundle 2 does), so overriding those parameters no
 * longer has any effect. Overriding the abstract service definitions
 * directly works on both major versions.
 */
class OverrideOrmClassesPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if ($container->hasDefinition('doctrine.orm.entity_manager.abstract')) {
            $container->getDefinition('doctrine.orm.entity_manager.abstract')->setClass(LoggingEntityManager::class);
        }

        if ($container->hasDefinition('doctrine.orm.configuration')) {
            $container->getDefinition('doctrine.orm.configuration')->setClass(LoggingConfiguration::class);
        }
    }
}
