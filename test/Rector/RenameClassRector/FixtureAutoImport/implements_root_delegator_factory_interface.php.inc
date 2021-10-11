<?php

namespace LaminasTest\ServiceManager\Migration\Rector\RenameClassRector\FixtureAutoImport;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Laminas\ServiceManager\DelegatorFactoryInterface;

class ImplementsRootInitializerFactoryInterface implements DelegatorFactoryInterface
{
    public function createDelegatorWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName, $callback)
    {
    }

    public function __invoke(ContainerInterface $container, $name, callable $callback, array $options = null)
    {
    }
}

?>
-----
<?php

namespace LaminasTest\ServiceManager\Migration\Rector\RenameClassRector\FixtureAutoImport;

use Laminas\ServiceManager\Factory\DelegatorFactoryInterface;
use Psr\Container\ContainerInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class ImplementsRootInitializerFactoryInterface implements DelegatorFactoryInterface
{
    public function createDelegatorWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName, $callback)
    {
    }

    public function __invoke(ContainerInterface $container, $name, callable $callback, array $options = null)
    {
    }
}

?>
