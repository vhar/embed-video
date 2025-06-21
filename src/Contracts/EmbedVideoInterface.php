<?php

namespace Vhar\EmbedVideo\Contracts;

use Vhar\EmbedVideo\Handlers\EmbedDataDTO;


interface EmbedVideoInterface
{
    /**
     * Handling Video URL
     * @param string $url
     * @return EmbedDataDTO
     */
    public function handle(string $url): EmbedDataDTO;

    /**
     * Allowed domains in input video URL
     * 
     * @return array
     */
    public function allowedDomains(): array;
}
