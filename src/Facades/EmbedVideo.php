<?php

namespace Vhar\EmbedVideo\Facades;

use Illuminate\Support\Facades\Facade;


/**
 * @method static \Vhar\EmbedVideo\Contracts\EmbedVideoInterface hosting(string $alias)
 * @method static \Vhar\EmbedVideo\Handlers\EmbedDataDTO handle(string $url)
 * 
 * @see \Vhar\EmbedVideo\EmbedVideoService
 */
class EmbedVideo extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'embedvideo';
    }
}
