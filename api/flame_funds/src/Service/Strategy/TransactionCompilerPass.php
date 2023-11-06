<?php

namespace App\Service\Strategy;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class TransactionCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $resolverService = $container->findDefinition(Transaction::class);

        $strategyServices = array_keys($container->findTaggedServiceIds('transaction_strategy'));

        foreach ($strategyServices as $strategyService) {
            $resolverService->addMethodCall('addStrategy', [new Reference($strategyService)]);
        }
    }
}