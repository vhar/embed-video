<?php

namespace Vhar\EmbedVideo\Handlers;

use Illuminate\Support\Facades\Http;
use Vhar\EmbedVideo\Handlers\EmbedDataDTO;
use Vhar\EmbedVideo\Contracts\EmbedVideoInterface;


class YouTubeHandler implements EmbedVideoInterface
{
    /**
     * YouTube url handler
     * @param string $url
     * @return EmbedDataDTO
     * @throws \InvalidArgumentException
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function handle(string $url): EmbedDataDTO
    {
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            throw new \InvalidArgumentException(__("The argument must be a URL string."));
        }

        $parts = parse_url($url);
        $parts['host'] = str_replace('www.', '', $parts['host']);

        if (
            !in_array($parts['host'], $this->allowedDomains())
            || empty($parts['path'])
            || empty(ltrim($parts['path'], '/'))
        ) {
            throw new \InvalidArgumentException(__("The argument is not valid URL-address to a YouTube video."));
        }

        $id = '';

        if ($parts['host'] === 'youtu.be') {
            $id = ltrim($parts['path'], '/');
        } elseif ($parts['host'] === 'youtube.com') {
            $path = explode('/', ltrim($parts['path'], '/'));

            switch ($path[0]) {
                case 'watch':
                    if (!empty($parts['query'])) {
                        parse_str($parts['query'], $query);
                        $id = $query['v'] ?? 'f';
                    }
                    break;
                case 'shorts':
                case 'embed':
                    $id = $path[1] ?? 'f';
                    break;
            }
        }

        if (empty($id)) {
            throw new \InvalidArgumentException(__("The argument is not valid URL-address to a YouTube video."));
        }

        $video = 'https://www.youtube.com/embed/' . $id;
        $cover = 'https://img.youtube.com/vi/' . $id . '/0.jpg';

        Http::get($video)->throwUnlessStatus(200);

        return new EmbedDataDTO(
            id: $id,
            video: $video,
            cover: $cover
        );
    }

    /**
     * Allowed YouTube domains
     * @return array
     */
    public function allowedDomains(): array
    {
        return [
            'youtu.be',
            'youtube.com',
        ];
    }
}
