<?php

namespace App\Service;

use App\Entity\Media;
use JsonException;

class VideoHelper
{
    public const PATTERN = '/(?:http:|https:|)\/\/(?:player.|www.)?(vimeo\.com|youtu(?:be\.com|\.be|be\.googleapis\.com))\/(?:video\/|embed\/|channels\/(?:\w+\/)|watch\?v=|v\/)?([A-Za-z0-9._%-]*)(\&\S+)?/ui';
    private const YOUTUBE_API_URL = 'https://www.youtube.com/get_video_info?video_id=';
    private const VIMEO_API_URL = 'https://vimeo.com/api/v2/video/';

    /**
     * @param string $url
     *
     * @return bool
     */
    public function checkUrl(string $url): bool
    {
        if (preg_match(self::PATTERN, $url)) {
            return true;
        }

        return false;
    }

    /**
     * @param string $url
     *
     * @throws JsonException
     *
     * @return array
     */
    public function getVideoData(string $url): array
    {
        $id = $this->getId($url);
        $type = Media::UNKNOWN;

        if (preg_match(self::PATTERN, $url, $matches)) {
            $type = $this->guessVideoType($matches[1]);
        }

        if (Media::YOUTUBE_VIDEO === $type) {
            $videoTitle = $this->getYoutubeVideoTitle($id);
        }

        if (Media::VIMEO_VIDEO === $type) {
            $videoTitle = $this->getVimeoVideoTitle($id);
        }

        return [
            'id' => $id,
            'title' => $videoTitle ?? 'Unknown video title',
            'type' => $type,
        ];
    }

    /**
     * @param string $url
     *
     * @return string
     */
    private function getId(string $url): string
    {
        if (preg_match(self::PATTERN, $url, $matches)) {
            return $matches[2];
        }

        return Media::UNKNOWN;
    }

    /**
     * @param string $host
     *
     * @return string
     */
    private function guessVideoType(string $host): string
    {
        if ('youtube.com' === $host || 'youtu.be' === $host) {
            return Media::YOUTUBE_VIDEO;
        }
        if ('vimeo.com' === $host) {
            return Media::VIMEO_VIDEO;
        }

        return Media::UNKNOWN;
    }

    /**
     * @param string $id
     *
     * @throws JsonException
     *
     * @return null|mixed
     */
    private function getYoutubeVideoTitle(string $id)
    {
        $apiUrl = self::YOUTUBE_API_URL.$id;
        parse_str(file_get_contents($apiUrl), $data);
        if (!isset($data['player_response'])) {
            return null;
        }

        $result = json_decode($data['player_response'], true, 512, JSON_THROW_ON_ERROR);

        return $result['videoDetails']['title'];
    }

    /**
     * @param string $id
     *
     * @throws JsonException
     *
     * @return null|mixed
     */
    private function getVimeoVideoTitle(string $id)
    {
        $apiUrl = self::VIMEO_API_URL.$id.'.json';
        $result = file_get_contents($apiUrl);
        $data = json_decode($result, true, 512, JSON_THROW_ON_ERROR);
        if (!isset($data)) {
            return null;
        }

        return $data[0]['title'];
    }
}
