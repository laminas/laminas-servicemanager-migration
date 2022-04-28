<?php

declare(strict_types=1);

use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(RenameClassRector::class, [
            // interop deprecated interop to psr
            'Interop\Container\ContainerInterface' => 'Psr\Container\ContainerInterface',

            // update deprecated root factory to new factory under new namespace
            'Laminas\ServiceManager\AbstractFactoryInterface'
                => 'Laminas\ServiceManager\Factory\AbstractFactoryInterface',
            'Laminas\ServiceManager\FactoryInterface'
                => 'Laminas\ServiceManager\Factory\FactoryInterface',
            'Laminas\ServiceManager\DelegatorFactoryInterface'
                => 'Laminas\ServiceManager\Factory\DelegatorFactoryInterface',
            'Laminas\ServiceManager\InitializerInterface'
                => 'Laminas\ServiceManager\Initializer\InitializerInterface',
        ]);
};
