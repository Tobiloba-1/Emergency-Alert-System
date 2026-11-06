<?php

class Settings
{
    private static ?array $cache = null;

    private static function loadAll(): array
    {
        if (self::$cache === null) {
            $stmt = Database::connection()->query('SELECT setting_key, setting_value FROM settings');
            self::$cache = [];
            foreach ($stmt->fetchAll() as $row) {
                self::$cache[$row['setting_key']] = $row['setting_value'];
            }
        }
        return self::$cache;
    }

    public static function get(string $key, ?string $default = null): ?string
    {
        $all = self::loadAll();
        return $all[$key] ?? $default;
    }

    public static function set(string $key, string $value): void
    {
        $stmt = Database::connection()->prepare(
            'INSERT INTO settings (setting_key, setting_value) VALUES (?, ?)
             ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)'
        );
        $stmt->execute([$key, $value]);
        self::$cache = null;
    }
}