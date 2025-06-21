<?php

namespace Vhar\EmbedVideo\Facades;

use Illuminate\Support\Facades\Facade;


/**
 * @method static void hosting(string $alias, string $class)
 * 
 * @see \Vhar\EmbedVideo\VideoHostingService
 */
class VideoHosting extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'videohosting';
    }
}
