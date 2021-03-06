<?php

namespace LaminasTest\ServiceManager\Migration\Rector\RenameClassRector\Fixture;

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

namespace LaminasTest\ServiceManager\Migration\Rector\RenameClassRector\Fixture;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Laminas\ServiceManager\DelegatorFactoryInterface;

class ImplementsRootInitializerFactoryInterface implements \Laminas\ServiceManager\Factory\DelegatorFactoryInterface
{
    public function createDelegatorWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName, $callback)
    {

    }

    public function __invoke(\Psr\Container\ContainerInterface $container, $name, callable $callback, array $options = null)
    {

    }
}

?>
