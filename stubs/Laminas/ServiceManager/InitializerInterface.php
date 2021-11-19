<?php

namespace Laminas\ServiceManager;

if (interface_exists('Laminas\ServiceManager\InitializerInterface')) {
    return;
}

interface InitializerInterface
{
}