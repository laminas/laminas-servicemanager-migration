<?php

// rector.php
declare(strict_types=1);

use Rector\Config\RectorConfig;
use Laminas\ServiceManager\Migration\Rector\Class_\ImplementsFactoryInterfaceToPsrFactoryRector;
use Laminas\ServiceManager\Migration\Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets([SetList::LAMINAS_SERVICEMANGER_40_AUTO_IMPORT]);
    $rectorConfig->rule(ImplementsFactoryInterfaceToPsrFactoryRector::class);
};
