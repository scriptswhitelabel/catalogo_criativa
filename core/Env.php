<?php

class Env {
    private static $loaded = false;
    private static $vars = [];

    private static function loadIfNeeded() {
        if (self::$loaded) { return; }
        self::$loaded = true;
        $root = dirname(__DIR__);
        $envPath = $root . DIRECTORY_SEPARATOR . '.env';
        if (!is_file($envPath)) { return; }
        $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $trim = trim($line);
            if ($trim === '' || strpos($trim, '#') === 0) { continue; }
            $parts = explode('=', $line, 2);
            if (count($parts) !== 2) { continue; }
            $key = trim($parts[0]);
            $value = trim($parts[1]);
            // Remover aspas envolventes
            if ((substr($value, 0, 1) === '"' && substr($value, -1) === '"') || (substr($value, 0, 1) === "'" && substr($value, -1) === "'")) {
                $value = substr($value, 1, -1);
            }
            self::$vars[$key] = $value;
        }
    }

    public static function get($key, $default = '') {
        self::loadIfNeeded();
        return array_key_exists($key, self::$vars) ? self::$vars[$key] : $default;
    }
}

?>


