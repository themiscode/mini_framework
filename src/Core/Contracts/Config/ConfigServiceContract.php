<?php

namespace App\Core\Contracts\Config;

interface ConfigServiceContract
{
    public function __construct(string $configPath);

    public function get(string $key, $default);

    public function has(string $key);
}