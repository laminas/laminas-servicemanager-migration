<?php

namespace LaminasTest\ServiceManager\Migration\Rector\RenameClassRector\FixtureAutoImport;

use Interop\Container\ContainerInterface;

class FactoryInvokeTypehint
{
    public function __invoke(ContainerInterface $container)
    {
    }
}

?>
-----
<?php

namespace LaminasTest\ServiceManager\Migration\Rector\RenameClassRector\FixtureAutoImport;

use Psr\Container\ContainerInterface;

class FactoryInvokeTypehint
{
    public function __invoke(ContainerInterface $container)
    {
    }
}

?>
