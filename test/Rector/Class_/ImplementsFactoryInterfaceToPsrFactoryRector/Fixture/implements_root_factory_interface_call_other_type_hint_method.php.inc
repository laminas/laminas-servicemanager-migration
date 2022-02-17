<?php

declare(strict_types=1);

namespace LaminasTest\ServiceManager\Migration\Rector\Class_\ImplementsFactoryInterfaceToPsrFactoryRector\Fixture;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class ServiceFactoryCallOtherTypeHintMethod implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $this->verify($container);
    }
    private function verify(ContainerInterface $container): ContainerInterface
    {
        return $container;
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace LaminasTest\ServiceManager\Migration\Rector\Class_\ImplementsFactoryInterfaceToPsrFactoryRector\Fixture;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class ServiceFactoryCallOtherTypeHintMethod
{
    public function __invoke(\Psr\Container\ContainerInterface $container)
    {
        $this->verify($container);
    }
    private function verify(\Psr\Container\ContainerInterface $container): \Psr\Container\ContainerInterface
    {
        return $container;
    }
}

?>
