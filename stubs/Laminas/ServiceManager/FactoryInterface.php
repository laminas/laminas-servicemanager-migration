<?php

declare(strict_types=1);

namespace Laminas\ServiceManager;

if (interface_exists('Laminas\ServiceManager\FactoryInterface')) {
    return;
}

interface FactoryInterface
{
}