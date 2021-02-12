<?php

namespace App\Service;

use App\Entity\Media;
use JsonException;

class VideoHelper
{
    public const BASE_PATTERN = '/(?:http:|https:|)\/\/(?:player.|www.)?(vimeo\.com|youtu(?:be\.com|\.be|be\.googleapis\.com))\/(?:video\/|embed\/|channels\/(?:\w+\/)|watch\?v=|v\/)?([A-Za-z0-9._%-]*)(\&\S+)?/ui';
    private const YOUTUBE_API_URL = 'https://www.youtube.com/get_video_info?video_id=';

    /**
     * @param string $url
     *
     * @return bool
     */
    public function checkUrl(string $url): bool
    {
        if (preg_match(self::BASE_PATTERN, $url)) {
            return true;
        }

        return false;
    }

    /**
     * @param string $url
     *
     * @throws JsonException
     *
     * @return null|array
     */
    public function getVideoData(string $url): ?array
    {
        $id = $this->getId($url);
        $apiUrl = self::YOUTUBE_API_URL.$id;
        parse_str(file_get_contents($apiUrl), $data);
        if (!isset($data['player_response'])) {
            return null;
        }

        $videoData = json_decode($data['player_response'], true, 512, JSON_THROW_ON_ERROR);

        return [
            'id' => $id,
            'title' => $videoData['videoDetails']['title'],
            'type' => $this->guessVideoType($url),
        ];
    }

    /**
     * @param string $url
     *
     * @return false|mixed
     */
    private function getId(string $url)
    {
        if (preg_match(self::BASE_PATTERN, $url, $matches)) {
            return $matches[2];
        }

        return false;
    }

    /**
     * @param string $url
     *
     * @return string
     */
    private function guessVideoType(string $url): string
    {
        if (preg_match(self::BASE_PATTERN, $url, $matches)) {
            if ('youtube.com' === $matches[1] || 'youtu.be' === $matches[1]) {
                return Media::YOUTUBE_VIDEO;
            }
            if ('vimeo.com' === $matches[1]) {
                return Media::VIMEO_VIDEO;
            }
        }

        return 'unknown';
    }
}
