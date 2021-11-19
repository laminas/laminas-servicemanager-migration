<?php

declare(strict_types=1);

namespace Laminas\ServiceManager;

if (interface_exists(DelegatorFactoryInterface::class)) {
    return;
}

interface DelegatorFactoryInterface
{
}
