<?php

namespace Dominant\Interfaces;

/**
 * Interface JsonIntegrated
 * @package Dominant\Interfaces
 */
interface JsonIntegrated
{
    public function getAsJSON(): string;

    public function getAsArray(): array;
}