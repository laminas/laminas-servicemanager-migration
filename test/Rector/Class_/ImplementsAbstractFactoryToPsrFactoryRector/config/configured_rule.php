<?php

declare(strict_types=1);

use Laminas\ServiceManager\Migration\Rector\Class_\ImplementsAbstractFactoryToPsrFactoryRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(ImplementsAbstractFactoryToPsrFactoryRector::class);
};
