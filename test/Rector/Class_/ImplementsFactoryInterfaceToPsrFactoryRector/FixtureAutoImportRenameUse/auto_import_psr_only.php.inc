<?php

declare(strict_types=1);

namespace LaminasTest\ServiceManager\Migration\Rector\Class_\FixtureAutoImportRenameUse\Fixture;

use Laminas\ServiceManager\Factory\FactoryInterface;

class AutoImportPsrOnly
{
    public function __invoke(\Psr\Container\ContainerInterface $container)
    {
        $this->verify($container);
    }
    private function verify(\Psr\Container\ContainerInterface $container): void
    {
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace LaminasTest\ServiceManager\Migration\Rector\Class_\FixtureAutoImportRenameUse\Fixture;

use Psr\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class AutoImportPsrOnly
{
    public function __invoke(ContainerInterface $container)
    {
        $this->verify($container);
    }
    private function verify(ContainerInterface $container): void
    {
    }
}

?>
