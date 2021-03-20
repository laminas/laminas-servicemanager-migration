<?php

namespace LaminasTest\ServiceManager\Migration\RenameClassRector\Fixture;

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

namespace LaminasTest\ServiceManager\Migration\RenameClassRector\Fixture;

use Laminas\ServiceManager\ServiceLocatorInterface;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\InitializerInterface;

class ImplementsRootInitializerInterface implements \Laminas\ServiceManager\Initializer\InitializerInterface
{
    public function initialize($instance, ServiceLocatorInterface $serviceLocator)
    {

    }

    public function __invoke(\Psr\Container\ContainerInterface $container, $instance)
    {

    }
}

?>
