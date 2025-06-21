<?php

namespace Vhar\EmbedVideo\Handlers;


final readonly class EmbedDataDTO
{
    /**
     * @param string $id video ID on hosting
     * @param string $video URL to embedded video 
     * @param string $cover URL to cover image
     */
    public function __construct(
        public string $id,
        public string $video,
        public string $cover
    ) {}
}
