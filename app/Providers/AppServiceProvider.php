<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\View\FileViewFinder;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Custom View Finder that supports both dotted view filenames (like member.dashboard.php)
        // and standard folder structure (like member/dashboard.php)
        $this->app->extend('view.finder', function ($finder, $app) {
            return new class($app['files'], $app['config']['view.paths']) extends FileViewFinder {
                protected function getPossibleViewFiles($name)
                {
                    $possible = [];
                    foreach ($this->extensions as $extension) {
                        $possible[] = str_replace('.', '/', $name).'.'.$extension;
                        $possible[] = $name.'.'.$extension;

                        if (str_contains($name, '.')) {
                            $parts = explode('.', $name);
                            $folder = implode('/', array_slice($parts, 0, -1));
                            $possible[] = $folder.'/'.$name.'.'.$extension;
                        }
                    }
                    return $possible;
                }
            };
        });
    }

    public function boot(): void
    {
        // Register .php files to be compiled using the Blade engine
        $this->app->view->addExtension('php', 'blade');

        // Dynamic Settings Loader & SMTP Config Binder
        $settingsFile = storage_path('app/website_settings.json');
        $settings = [];
        if (file_exists($settingsFile)) {
            $settings = json_decode(file_get_contents($settingsFile), true);
        } else {
            // Default fallbacks
            $settings = [
                'website_name' => 'Sistem Informasi Manajemen UKM PSUP',
                'footer_text' => '© 2026 Paduan Suara Universitas Pancasila. All rights reserved.',
                'contact_email' => 'psup@univpancasila.ac.id',
                'contact_phone' => '081234567890',
                'address' => 'Gedung UKM Lt. 2 Universitas Pancasila, Srengseng Sawah, Jagakarsa, Jakarta Selatan',
                'facebook_url' => 'https://facebook.com/psup',
                'instagram_url' => 'https://instagram.com/psup',
                'youtube_url' => 'https://youtube.com/psup',
                'smtp_host' => 'smtp.gmail.com',
                'smtp_port' => '587',
                'smtp_user' => 'psup@univpancasila.ac.id',
                'smtp_pass' => 'password123',
                'smtp_enc' => 'tls',
                'notify_email' => '1',
                'notify_system' => '1',
                'notify_announce' => '1',
                'storage_driver' => 'public'
            ];
        }

        // Apply settings to global configuration
        config([
            'app.name' => $settings['website_name'] ?? config('app.name'),
            'mail.mailers.smtp.host' => $settings['smtp_host'] ?? config('mail.mailers.smtp.host'),
            'mail.mailers.smtp.port' => $settings['smtp_port'] ?? config('mail.mailers.smtp.port'),
            'mail.mailers.smtp.username' => $settings['smtp_user'] ?? config('mail.mailers.smtp.username'),
            'mail.mailers.smtp.password' => $settings['smtp_pass'] ?? config('mail.mailers.smtp.password'),
            'mail.mailers.smtp.encryption' => $settings['smtp_enc'] ?? config('mail.mailers.smtp.encryption'),
            'mail.from.address' => $settings['contact_email'] ?? config('mail.from.address'),
            'mail.from.name' => $settings['website_name'] ?? config('mail.from.name'),
        ]);

        // Share settings globally with all views
        View::share('websiteSettings', $settings);
    }
}