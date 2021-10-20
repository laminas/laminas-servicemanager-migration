<?php

namespace Laminas\ServiceManager\Migration\Rector\Class_;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use Rector\Core\Rector\AbstractRector;
use Laminas\ServiceManager\Factory\FactoryInterface;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Use_;
use PhpParser\Node\Stmt\UseUse;
use Rector\Core\Configuration\Option;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\Renaming\NodeManipulator\ClassRenamer;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ImplementsFactoryInterfaceToPsrFactoryRector extends AbstractRector
{
    private const FACTORY_INTERFACE = FactoryInterface::class;

    /** @var ClassRenamer */
    private $classRenamer;

    public function __construct(ClassRenamer $classRenamer)
    {
        $this->classRenamer = $classRenamer;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Rector implements ServiceManager FactoryInterface to Psr Factory', [
            new CodeSample(
                <<<'CODE_SAMPLE'
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

        foreach ($node->implements as $key => $implement) {
            if ($this->nodeNameResolver->isName($implement, self::FACTORY_INTERFACE)) {
                unset($node->implements[$key]);
            }
        }

        $firstParam = $invokeMethod->params[0];
        $invokeMethod->params = [];
        $firstParam->type = new FullyQualified('Psr\Container\ContainerInterface');
        $invokeMethod->params[0] = $firstParam;

        $this->replaceUseInteropStatementOnAutoImportEnabled($node);

        return $node;
    }

    private function replaceUseInteropStatementOnAutoImportEnabled(Class_ $class)
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
