<?php

namespace App\Core\Services;

use App\Core\Contracts\Config\ConfigServiceContract;

class ConfigService implements ConfigServiceContract
{
    protected array $configs;

    public function __construct(string $configPath) 
    {
        $configs = [];

        if (!$configPath || !is_dir($configPath)) {
            $this->configs = $configs;
        }

        if ($dirHandle = opendir($configPath)) {
            while(($file = readdir($dirHandle)) !== false) {
                if ($file != '.' && $file != '..') {
                    $configs[basename($file, '.php')] = include $configPath . '/' . $file;
                }
            }

            closedir($dirHandle);
        }

        $this->configs = $configs;
    }

    public function get(string $key, $default = null) 
    {
        return is_null(($value = $this->parseArray($key)))
            ? $default
            : $value;
    }

    public function has(string $key) 
    {
        return is_null($this->parseArray($key));
    }

    protected function parseArray(string $key) 
    {
        $tokens = explode('.', $key);

        $context = $this->configs;

        while (($token = array_shift($tokens)) !== null) {
            if (!isset($context[$token])) {
                return false;
            }

            $context = $context[$token];
        }

        return $context;
    }
}