<?php

declare(strict_types=1);

namespace LaminasTest\ServiceManager\Migration\Rector\Class_\ImplementsAbstractFactoryToPsrFactoryRector\Fixture;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\AbstractFactoryInterface;

class ImplementsRootAbstractFactoryInterface implements AbstractFactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

    }
}

?>
-----
<?php

declare(strict_types=1);

namespace LaminasTest\ServiceManager\Migration\Rector\Class_\ImplementsAbstractFactoryToPsrFactoryRector\Fixture;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\AbstractFactoryInterface;

class ImplementsRootAbstractFactoryInterface
{
    public function __invoke(\Psr\Container\ContainerInterface $container)
    {

    }
}

?>
