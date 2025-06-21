<?php

namespace Vhar\EmbedVideo\Handlers;

use Illuminate\Support\Facades\Http;
use Vhar\EmbedVideo\Handlers\EmbedDataDTO;
use Illuminate\Http\Client\HttpClientException;
use Vhar\EmbedVideo\Contracts\EmbedVideoInterface;


class RutubeHandler implements EmbedVideoInterface
{
    /**
     * Rutube url handler
     * @param string $url
     * @return EmbedDataDTO
     * @throws \InvalidArgumentException
     * @throws \Illuminate\Http\Client\HttpClientException;
     */
    public function handle(string $url): EmbedDataDTO
    {
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            throw new \InvalidArgumentException(__("The argument must be a URL string."));
        }

        $parts = parse_url($url);
        $parts['host'] = str_replace('www.', '', $parts['host']);

        if (!in_array($parts['host'], $this->allowedDomains())) {
            throw new \InvalidArgumentException(__("The argument is not valid URL-address to a Rutube video."));
        }

        if (empty($parts['path'])) {
            throw new \InvalidArgumentException(__("The argument is not valid URL-address to a Rutube video."));
        }

        $path = explode('/', trim($parts['path'], '/'));

        $id = array_pop($path);

        if (empty($id) || !preg_match("/^[0-9a-f]{32}$/", $id)) {
            throw new \InvalidArgumentException(__("The argument is not valid URL-address to a Rutube video."));
        }

        $dataURL = sprintf("https://rutube.ru/api/video/%s/?format=json&%s", $id, $parts['query'] ?? '');

        $videoData = Http::get($dataURL);

        if ($videoData->status() !== 200) {
            throw new HttpClientException(json_encode($videoData->json()));
        }

        $video = $videoData->json('embed_url');
        $cover = $videoData->json('thumbnail_url');

        Http::get($video)->throwUnlessStatus(200);

        return new EmbedDataDTO(
            id: $id,
            video: $video,
            cover: $cover
        );
    }

    /**
     * Allowed Rutube domains
     * @return array
     */
    public function allowedDomains(): array
    {
        return [
            'rutube.ru',
        ];
    }
}
