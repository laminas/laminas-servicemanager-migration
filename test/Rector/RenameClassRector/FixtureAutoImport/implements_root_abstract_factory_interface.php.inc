<?php

namespace LaminasTest\ServiceManager\Migration\Rector\RenameClassRector\FixtureAutoImport;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Laminas\ServiceManager\AbstractFactoryInterface;

class ImplementsRootAbstractFactoryInterface implements AbstractFactoryInterface
{
    public function canCreateServiceWithName(ServiceLocatorInterface $locator, $name, $requestedName)
    {
    }

    public function createServiceWithName(ServiceLocatorInterface $locator, $name, $requestedName)
    {
    }

    public function canCreate(ContainerInterface $container, $requestedName)
    {
    }

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
    }
}

?>
-----
<?php

namespace LaminasTest\ServiceManager\Migration\Rector\RenameClassRector\FixtureAutoImport;

use Laminas\ServiceManager\Factory\AbstractFactoryInterface;
use Psr\Container\ContainerInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class ImplementsRootAbstractFactoryInterface implements AbstractFactoryInterface
{
    public function canCreateServiceWithName(ServiceLocatorInterface $locator, $name, $requestedName)
    {
    }

    public function createServiceWithName(ServiceLocatorInterface $locator, $name, $requestedName)
    {
    }

    public function canCreate(ContainerInterface $container, $requestedName)
    {
    }

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
    }
}

?>
