<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Laminas\ServiceManager\Migration\Rector\Class_\ImplementsFactoryInterfaceToPsrFactoryRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(ImplementsFactoryInterfaceToPsrFactoryRector::class);
};
