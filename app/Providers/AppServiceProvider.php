<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../vendor/tinymce/tinymce/plugins' => public_path('packages/tinymce/plugins'),
            __DIR__ . '/../../vendor/tinymce/tinymce/skins' => public_path('packages/tinymce/skins'),
            __DIR__ . '/../../vendor/tinymce/tinymce/themes' => public_path('packages/tinymce/themes'),
            __DIR__ . '/../../vendor/tinymce/tinymce/jquery.tinymce.min.js' => public_path('packages/tinymce/jquery.tinymce.min.js'),
            __DIR__ . '/../../vendor/tinymce/tinymce/tinymce.min.js' => public_path('packages/tinymce/tinymce.min.js'),
        ], 'tinymce');

        $this->publishes([
            __DIR__ . '/../../vendor/studio-42/elfinder/css' => public_path('packages/elfinder/css'),
            __DIR__ . '/../../vendor/studio-42/elfinder/img' => public_path('packages/elfinder/img'),
            __DIR__ . '/../../vendor/studio-42/elfinder/js' => public_path('packages/elfinder/js'),
            __DIR__ . '/../../vendor/studio-42/elfinder/sounds' => public_path('packages/elfinder/sounds'),
        ], 'elfinder');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
