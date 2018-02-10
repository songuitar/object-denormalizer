<?php

namespace Songuitar\Denormalizer\Test;

use PHPUnit\Framework\TestCase;
use Songuitar\Denormalizer\Denormalizer;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class ContainerAwareTestCase extends TestCase
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function setUp()
    {
        parent::setUp();
        $container = new ContainerBuilder();
        $container->register(Denormalizer::class);
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__));
        $loader->load('services.yml');
        $this->container = $container;
    }
}