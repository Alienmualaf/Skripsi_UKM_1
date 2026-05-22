<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class SettingService
{
    protected static $path = 'settings.json';

    public static function all()
    {
        if (!Storage::exists(self::$path)) {
            $default = [
                'app_name' => 'Sistem UKM',
                'university_name' => 'Universitas Pancasila',
                'admin_contact' => 'admin@univpancasila.ac.id',
                'registration_status' => 'open',
                'maintenance_mode' => 'inactive'
            ];
            Storage::put(self::$path, json_encode($default, JSON_PRETTY_PRINT));
            return $default;
        }

        $content = Storage::get(self::$path);
        return json_decode($content, true) ?: [];
    }

    public static function get($key, $default = null)
    {
        $settings = self::all();
        return $settings[$key] ?? $default;
    }

    public static function set($key, $value)
    {
        $settings = self::all();
        $settings[$key] = $value;
        Storage::put(self::$path, json_encode($settings, JSON_PRETTY_PRINT));
    }

    public static function saveMany(array $data)
    {
        $settings = self::all();
        foreach ($data as $key => $val) {
            $settings[$key] = $val;
        }
        Storage::put(self::$path, json_encode($settings, JSON_PRETTY_PRINT));
    }
}
