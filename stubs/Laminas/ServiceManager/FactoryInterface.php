<?php

declare(strict_types=1);

namespace Laminas\ServiceManager;

if (interface_exists(FactoryInterface::class)) {
    return;
}

interface FactoryInterface
{
}
