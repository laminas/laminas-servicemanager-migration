<?php

declare(strict_types=1);

use Laminas\ServiceManager\Migration\Rector\Class_\ImplementsFactoryInterfaceToToPsrFactoryRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(ImplementsFactoryInterfaceToToPsrFactoryRector::class);
};
