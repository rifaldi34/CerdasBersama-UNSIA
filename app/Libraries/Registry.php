<?php namespace App\Libraries;

class Registry
{
    protected static array $data = [];

    //JANGAN DIGUNAKAN UNTUK USER DATA, BISA BOCOR (DATA LEAKAGE)

    public static function set(string $key, $value): void
    {
        static::$data[$key] = $value;
    }

    public static function get(string $key, $default = null)
    {
        return static::$data[$key] ?? $default;
    }
}