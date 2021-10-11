<?php

namespace LaminasTest\ServiceManager\Migration\Rector\RenameClassRector\FixtureAutoImport;

use Laminas\ServiceManager\FactoryInterface;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class ImplementsRootFactoryInterface implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $sl)
    {
    }

    public function __invoke(ContainerInterface $container, $requestedName, ?array $option = null)
    {
    }
}

?>
-----
<?php

namespace LaminasTest\ServiceManager\Migration\Rector\RenameClassRector\FixtureAutoImport;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class ImplementsRootFactoryInterface implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $sl)
    {
    }

    public function __invoke(ContainerInterface $container, $requestedName, ?array $option = null)
    {
    }
}

?>
