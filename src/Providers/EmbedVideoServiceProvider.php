<?php

namespace Vhar\EmbedVideo\Providers;

use Vhar\EmbedVideo\EmbedVideoService;
use Illuminate\Support\ServiceProvider;
use Vhar\EmbedVideo\VideoHostingService;
use Vhar\EmbedVideo\Facades\VideoHosting;
use Vhar\EmbedVideo\Handlers\RutubeHandler;
use Vhar\EmbedVideo\Handlers\VKVideoHandler;
use Vhar\EmbedVideo\Handlers\YouTubeHandler;


class EmbedVideoServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadJsonTranslationsFrom(__DIR__ . '/../../resources/lang');

        $this->publishes([
            __DIR__ . '/../../resources/lang' => $this->app->langPath('vhar/embed-video'),
        ]);
    }

    /**
     * Register services.
     * 
     * @return void
     */
    public function register()
    {
        $this->registerVideoHosting();
        $this->registerEmbedVideo();
    }

    /**
     * Registering main handler class
     * 
     * @return void
     */
    public function registerEmbedVideo()
    {
        $this->app->singleton('embedvideo', function () {
            return new EmbedVideoService();
        });

        $this->app->bind('embedvideo.hosting', function ($app) {
            return $app->make('embedvideo')->hosting();
        });
    }

    /**
     * Registering handler classes for video hosting
     * 
     * @return void
     */
    public function registerVideoHosting()
    {
        $this->app->singleton('videohosting', function () {
            return new VideoHostingService();
        });

        VideoHosting::hosting('rutube', RutubeHandler::class);
        VideoHosting::hosting('youtube', YouTubeHandler::class);
        VideoHosting::hosting('vkvideo', VKVideoHandler::class);
    }
}
