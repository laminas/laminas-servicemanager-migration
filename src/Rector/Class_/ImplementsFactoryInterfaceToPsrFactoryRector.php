<?php

namespace Laminas\ServiceManager\Migration\Rector\Class_;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use Rector\Core\Rector\AbstractRector;
use Laminas\ServiceManager\Factory\FactoryInterface;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Use_;
use Rector\Core\Configuration\Option;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ImplementsFactoryInterfaceToPsrFactoryRector extends AbstractRector
{
    private const FACTORY_INTERFACE = FactoryInterface::class;

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Rector implements ServiceManager FactoryInterface to Psr Factory', [
            new CodeSample(
                <<<'CODE_SAMPLE'
                use Interop\Container\ContainerInterface;

                class ImplementsRootAbstractFactoryInterface implements FactoryInterface
                {
                    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
                    {

                    }
                }
                CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
                class ImplementsRootAbstractFactoryInterface
                {
                    public function __invoke(\Psr\Container\ContainerInterface $container)
                    {

                    }
                }
                CODE_SAMPLE
            ),
        ]);
    }

    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }

        $invokeMethod = $node->getMethod('__invoke');
        if (! $invokeMethod instanceof ClassMethod) {
            return null;
        }

        $this->removeFactoryInterfaceFromImplements($node);
        $this->replaceInteropParam($node);
        $this->replaceInvokeClassMethodParams($invokeMethod);
        $this->replaceUseInteropStatementOnAutoImportEnabled($node);

        return $node;
    }

    private function shouldSkip(Node $node): bool
    {
        $implements = $node->implements;
        foreach ($implements as $implement) {
            if (! $implement instanceof FullyQualified) {
                continue;
            }

            if ($this->nodeNameResolver->isName($implement, self::FACTORY_INTERFACE)) {
                return false;
            }
        }

        return true;
    }

    private function removeFactoryInterfaceFromImplements(Class_ $class): void
    {
        foreach ($class->implements as $key => $implement) {
            if ($this->nodeNameResolver->isName($implement, self::FACTORY_INTERFACE)) {
                unset($class->implements[$key]);
            }
        }
    }

    private function replaceInteropParam(Class_ $class): void
    {
        $this->traverseNodesWithCallable($class, function (Node $subNode): ?FullyQualified {
            if (! $subNode instanceof FullyQualified) {
                return null;
            }

            if (! $this->nodeNameResolver->isName($subNode, 'Interop\Container\ContainerInterface')) {
                return null;
            }

            $subNode->parts[0] = 'Psr';
            return $subNode;
        });
    }

    private function replaceInvokeClassMethodParams(ClassMethod $classMethod): void
    {
        $params = $classMethod->getParams();
        foreach (array_keys($params) as $key) {
            if ($key > 0) {
                unset($params[$key]);
                continue;
            }

            $params[$key]->type = new FullyQualified('Psr\Container\ContainerInterface');
        }
        $classMethod->params = $params;
    }

    private function replaceUseInteropStatementOnAutoImportEnabled(Class_ $class): void
    {
        if (! $this->parameterProvider->provideBoolParameter(Option::AUTO_IMPORT_NAMES)) {
            return;
        }

        $use = $this->betterNodeFinder->findFirstPreviousOfNode($class, function (Node $subNode): bool {
            if (! $subNode instanceof Use_) {
                return false;
            }

            $uses = $subNode->uses;
            if (count($uses) > 1) { // skip group
                return false;
            }

            if ($uses[0]->alias instanceof Identifier) {
                return false;
            }

            return $uses[0]->name->toString() === 'Interop\Container\ContainerInterface';
        });

        if ($use instanceof Use_) {
            $this->removeNode($use);
        }
    }
}
