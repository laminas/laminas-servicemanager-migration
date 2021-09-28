<?php

namespace LaminasTest\ServiceManager\Migration\Rector\RenameClassRector\FixtureAutoImport;

use Laminas\ServiceManager\ServiceLocatorInterface;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\InitializerInterface;

class ImplementsRootInitializerInterface implements InitializerInterface
{
    public function initialize($instance, ServiceLocatorInterface $serviceLocator)
    {
    }

    public function __invoke(ContainerInterface $container, $instance)
    {
    }
}

?>
-----
<?php

namespace LaminasTest\ServiceManager\Migration\Rector\RenameClassRector\FixtureAutoImport;

use Laminas\ServiceManager\Initializer\InitializerInterface;
use Psr\Container\ContainerInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class ImplementsRootInitializerInterface implements InitializerInterface
{
    public function initialize($instance, ServiceLocatorInterface $serviceLocator)
    {
    }

    public function __invoke(ContainerInterface $container, $instance)
    {
    }
}

?>
