<?php

namespace Laminas\ServiceManager\Migration\Rector\Class_;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use Laminas\ServiceManager\Factory\FactoryInterface;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Use_;
use PhpParser\Node\Stmt\UseUse;
use Rector\Configuration\Option;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\PhpParser\Node\CustomNode\FileWithoutNamespace;
use Rector\Naming\Naming\UseImportsResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ImplementsFactoryInterfaceToPsrFactoryRector extends AbstractRector
{
    private const FACTORY_INTERFACE = FactoryInterface::class;

    public function __construct(
        private UseImportsResolver $useImportsResolver,
        private BetterNodeFinder $betterNodeFinder
    ) {
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
        return [Class_::class, FileWithoutNamespace::class, Namespace_::class];
    }

    /**
     * @param Class_|FileWithoutNamespace|Namespace_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node instanceof FileWithoutNamespace || $node instanceof Namespace_) {
            return $this->replaceUseInteropStatementOnAutoImportEnabled($node);
        }

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

    private function replaceUseInteropStatementOnAutoImportEnabled(
        FileWithoutNamespace|Namespace_ $namespace
    ): FileWithoutNamespace|Namespace_|null {
        if (! SimpleParameterProvider::provideBoolParameter(Option::AUTO_IMPORT_NAMES)) {
            return null;
        }

        /** @var Use_[] $uses */
        $uses = array_filter($namespace->stmts, fn (Stmt $stmt): bool => $stmt instanceof Use_);

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
                $stmtKey = $use->getAttribute(AttributeKey::STMT_KEY);
                unset($namespace->stmts[$stmtKey]);

                return $namespace;
            }
        }

        return null;
    }
}
