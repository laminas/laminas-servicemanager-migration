<?php

namespace Laminas\ServiceManager\Migration\Rector\Class_;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use Rector\Core\Rector\AbstractRector;
use Laminas\ServiceManager\AbstractFactoryInterface;
use Laminas\ServiceManager\Factory\AbstractFactoryInterface as NewAbstractFactoryInterface;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;

final class ImplementsAbstractFactoryToPsrFactoryRector extends AbstractRector
{
    private const ABSTRACT_FACTORIES = [
        AbstractFactoryInterface::class,
        NewAbstractFactoryInterface::class,
    ];

    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        $extends = $node->extends;
        if (! $extends instanceof FullyQualified) {
            return null;
        }

        if (! $this->nodeNameResolver->isNames($extends, self::ABSTRACT_FACTORIES)) {
            return null;
        }

        $invokeMethod = $node->getMethod('__invoke');
        if (! $invokeMethod instanceof ClassMethod) {
            return null;
        }

        $node->extends = null;
        $node->params = [
            new Param(new FullyQualified('Psr\Container\ContainerInterface'))
        ];
        return $node;
    }
}