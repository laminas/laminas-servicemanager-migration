<?php

// rector.php
declare(strict_types=1);

use Laminas\ServiceManager\Migration\Rector\Class_\ImplementsFactoryInterfaceToPsrFactoryRector;
use Laminas\ServiceManager\Migration\Rector\Set\ValueObject\SetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(SetList::LAMINAS_SERVICEMANGER_40_AUTO_IMPORT);

    $services = $containerConfigurator->services();
    $services->set(ImplementsFactoryInterfaceToPsrFactoryRector::class);
};
