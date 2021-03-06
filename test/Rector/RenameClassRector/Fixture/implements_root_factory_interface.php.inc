<?php

namespace LaminasTest\ServiceManager\Migration\Rector\RenameClassRector\Fixture;

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

namespace LaminasTest\ServiceManager\Migration\Rector\RenameClassRector\Fixture;

use Laminas\ServiceManager\FactoryInterface;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class ImplementsRootFactoryInterface implements \Laminas\ServiceManager\Factory\FactoryInterface
{
    public function createService(ServiceLocatorInterface $sl)
    {

    }

    public function __invoke(\Psr\Container\ContainerInterface $container, $requestedName, ?array $option = null)
    {

    }
}

?>
