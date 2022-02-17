<?php

namespace LaminasTest\ServiceManager\Migration\Rector\RenameClassRector\Fixture;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Laminas\ServiceManager\AbstractFactoryInterface;

class ServiceFactory implements AbstractFactoryInterface
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

namespace LaminasTest\ServiceManager\Migration\Rector\RenameClassRector\Fixture;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Laminas\ServiceManager\AbstractFactoryInterface;

class ServiceFactory implements \Laminas\ServiceManager\Factory\AbstractFactoryInterface
{
    public function canCreateServiceWithName(ServiceLocatorInterface $locator, $name, $requestedName)
    {

    }

    public function createServiceWithName(ServiceLocatorInterface $locator, $name, $requestedName)
    {

    }

    public function canCreate(\Psr\Container\ContainerInterface $container, $requestedName)
    {

    }

    public function __invoke(\Psr\Container\ContainerInterface $container, $requestedName, array $options = null)
    {

    }
}

?>
