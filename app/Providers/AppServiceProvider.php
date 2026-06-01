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
    }
}