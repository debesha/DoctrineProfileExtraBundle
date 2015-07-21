<?php

    namespace Debesha\DoctrineProfileExtraBundle\DependencyInjection;

    use Symfony\Component\DependencyInjection\ContainerBuilder;
    use Symfony\Component\Config\FileLocator;
    use Symfony\Component\HttpKernel\DependencyInjection\Extension;
    use Symfony\Component\DependencyInjection\Loader;

    /**
     * This is the class that loads and manages your bundle configuration
     *
     * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
     */
    class DebeshaDoctrineProfileExtraExtension extends Extension
    {

        /**
         * {@inheritdoc}
         */
        public function load(array $configs, ContainerBuilder $container)
        {

            if (!$container->hasParameter("doctrine.orm.entity_manager.class")) {

                throw new \InvalidArgumentException("You must include DoctrineBundle/DoctrineBundle before DebeshaDoctrineProfileExtraBundle in your AppKerner.php");
            }

            $configuration = new Configuration();
            $config = $this->processConfiguration($configuration, $configs);


            $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
            $loader->load('services.xml');
        }
    }
