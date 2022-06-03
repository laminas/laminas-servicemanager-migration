<?php

namespace Laminas\ServiceManager\Migration\Rector\Class_;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use Rector\Core\Rector\AbstractRector;
use Laminas\ServiceManager\Factory\FactoryInterface;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\UseUse;
use Rector\Core\Configuration\RectorConfigProvider;
use Rector\Naming\Naming\UseImportsResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ImplementsFactoryInterfaceToPsrFactoryRector extends AbstractRector
{
    private const FACTORY_INTERFACE = FactoryInterface::class;

    private RectorConfigProvider $rectorConfigProvider;
    private UseImportsResolver $useImportsResolver;

    public function __construct(
        RectorConfigProvider $rectorConfigProvider,
        UseImportsResolver $useImportsResolver
    ) {
        $this->rectorConfigProvider = $rectorConfigProvider;
        $this->useImportsResolver = $useImportsResolver;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Rector implements ServiceManager FactoryInterface to Psr Factory', [
            new CodeSample(
                <<<'CODE_SAMPLE'
                use Interop\Container\ContainerInterface;

                class ServiceFactory implements FactoryInterface
                {
                    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
                    {

                    }
                }
                CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
                class ServiceFactory
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

        /**
         * @var ClassMethod $invokeMethod
         */
        $invokeMethod = $node->getMethod('__invoke');

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
                return $this->doesNotHasInvokeMethodOrRequestedNameOrOptionsParamUsed($node);
            }
        }

        return true;
    }

    private function doesNotHasInvokeMethodOrRequestedNameOrOptionsParamUsed(Class_ $class): bool
    {
        $invoke = $class->getMethod('__invoke');

        if (! $invoke instanceof ClassMethod) {
            return true;
        }

        $params = $invoke->getParams();
        foreach ($params as $key => $param) {
            if ($key > 0) {
                $isUsed = (bool) $this->betterNodeFinder->findFirstInFunctionLikeScoped(
                    $invoke,
                    fn (Node $subNode): bool => $this->nodeComparator->areNodesEqual($subNode, $param->var)
                );

                if ($isUsed) {
                    return true;
                }
            }
        }

        return false;
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
        if (! $this->rectorConfigProvider->shouldImportNames()) {
            return;
        }

        $uses = $this->useImportsResolver->resolveBareUsesForNode($class);

        foreach ($uses as $use) {
            /** @var UseUse|false $useUse */
            $useUse = current($use->uses);

            if (! $useUse instanceof UseUse) {
                continue;
            }

            if ($useUse->alias instanceof Identifier) {
                continue;
            }

            if ($useUse->name->toString() === 'Interop\Container\ContainerInterface') {
                $this->removeNode($use);
                break;
            }
        }
    }
}
