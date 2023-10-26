<?php
declare(strict_types=1);


namespace App\Service\Strategy;

use App\Service\Interfaces\CategoryInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;


class CategoryCompilerPass implements CompilerPassInterface
{

    public function process(ContainerBuilder $container)
    {
        $resolverService = $container->findDefinition(Category::class);

        $strategyServices = array_keys($container->findTaggedServiceIds('category_strategy'));

        foreach ($strategyServices as $strategyService) {
            $resolverService->addMethodCall('addStrategy', [new Reference($strategyService)]);
        }
    }
}