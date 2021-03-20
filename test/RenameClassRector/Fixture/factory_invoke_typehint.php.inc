<?php

namespace LaminasTest\ServiceManager\Migration\RenameClassRector\Fixture;

use Interop\Container\ContainerInterface;
use LaminasTest\ServiceManager\Migration\RenameClassRector\Source\Service;

class FactoryInvokeTypehint
{
	public function __invoke(ContainerInterface $container)
	{
		$adapter = $container->get('example');

        return new Service($adapter);
	}
}

?>
-----
<?php

namespace LaminasTest\ServiceManager\Migration\RenameClassRector\Fixture;

use Interop\Container\ContainerInterface;
use LaminasTest\ServiceManager\Migration\RenameClassRector\Source\Service;

class FactoryInvokeTypehint
{
	public function __invoke(\Psr\Container\ContainerInterface $container)
	{
		$adapter = $container->get('example');

        return new Service($adapter);
	}
}

?>
