<?php

namespace Debesha\DoctrineProfileExtraBundle;

use Debesha\DoctrineProfileExtraBundle\DependencyInjection\Compiler\OverrideOrmClassesPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class DebeshaDoctrineProfileExtraBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new OverrideOrmClassesPass());
    }
}
