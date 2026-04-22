<?php

if (!function_exists('config')) {
    /**
     * Load a configuration file from the config directory.
     */
    function config(string $key): array
    {
        static $cache = [];

        if (!isset($cache[$key])) {
            $path = __DIR__ . '/../config/' . $key . '.php';

            if (!file_exists($path)) {
                throw new \RuntimeException("Config file not found: {$path}");
            }

            $cache[$key] = require $path;
        }

        return $cache[$key];
    }
}
