<?php

declare(strict_types=1);

namespace Interop\Container;

if (interface_exists(ContainerInterface::class)) {
    return;
}

interface ContainerInterface
{
}
