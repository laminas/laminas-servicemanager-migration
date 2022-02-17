<?php

declare(strict_types=1);

namespace LaminasTest\ServiceManager\Migration\Rector\Class_\FixtureAutoImportRenameUse\Fixture;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class ServiceFactoryCallOtherTypeHintMethod implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $this->verify($container);
    }
    private function verify(ContainerInterface $container): ContainerInterface
    {
        return $container;
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace LaminasTest\ServiceManager\Migration\Rector\Class_\FixtureAutoImportRenameUse\Fixture;

use Psr\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class ServiceFactoryCallOtherTypeHintMethod
{
    public function __invoke(ContainerInterface $container)
    {
        $this->verify($container);
    }
    private function verify(ContainerInterface $container): ContainerInterface
    {
        return $container;
    }
}

?>
