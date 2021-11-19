<?php

namespace Laminas\ServiceManager;

if (interface_exists(InitializerInterface::class)) {
    return;
}

interface InitializerInterface
{
}
