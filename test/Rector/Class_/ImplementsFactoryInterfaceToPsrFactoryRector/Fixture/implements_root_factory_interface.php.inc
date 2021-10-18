<?php

declare(strict_types=1);

namespace LaminasTest\ServiceManager\Migration\Rector\Class_\ImplementsFactoryInterfaceToPsrFactoryRector\Fixture;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class ImplementsRootAbstractFactoryInterface implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

    }
}

?>
-----
<?php

declare(strict_types=1);

namespace LaminasTest\ServiceManager\Migration\Rector\Class_\ImplementsFactoryInterfaceToPsrFactoryRector\Fixture;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class ImplementsRootAbstractFactoryInterface
{
    public function __invoke(\Psr\Container\ContainerInterface $container)
    {

    }
}

?>
