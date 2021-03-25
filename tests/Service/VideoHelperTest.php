<?php

namespace Tests\Service;

use App\Service\VideoHelper;
use JsonException;
use PHPUnit\Framework\TestCase;

class VideoHelperTest extends TestCase
{
    /**
     * @var VideoHelper
     */
    private VideoHelper $videoHelper;

    protected function setUp(): void
    {
        $this->videoHelper = new VideoHelper();
    }

    /**
     * @dataProvider getYoutubeUrl
     *
     * @param string $url
     * @param bool   $isValidUrl
     */
    public function testCheckUrl(string $url, bool $isValidUrl): void
    {
        self::assertSame($isValidUrl, $this->videoHelper->checkUrl($url));
    }

    /**
     * @dataProvider getYoutubeUrl
     *
     * @param string $url
     * @param bool   $isValidUrl
     *
     * @throws JsonException
     */
    public function testGetYoutubeVideoData(string $url, bool $isValidUrl): void
    {
        $videoData = $this->videoHelper->getVideoData($url);
        if ($isValidUrl) {
            self::assertArrayHasKey('id', $videoData);
            self::assertEquals('Z3MohNj9eVo', $videoData['id']);
            self::assertArrayHasKey('title', $videoData);
            self::assertEquals('7 simple photography hacks', $videoData['title']);
            self::assertArrayHasKey('type', $videoData);
            self::assertEquals('youtube', $videoData['type']);
        } else {
            self::assertArrayHasKey('id', $videoData);
            self::assertEquals('unknown', $videoData['id']);
            self::assertArrayHasKey('title', $videoData);
            self::assertEquals('Unknown video title', $videoData['title']);
            self::assertArrayHasKey('type', $videoData);
            self::assertEquals('unknown', $videoData['type']);
        }
    }

    /**
     * @dataProvider getVimeoUrl
     *
     * @param string $url
     * @param bool   $isValidUrl
     *
     * @throws JsonException
     */
    public function testGetVimeoVideoData(string $url, bool $isValidUrl): void
    {
        $videoData = $this->videoHelper->getVideoData($url);
        if ($isValidUrl) {
            self::assertArrayHasKey('id', $videoData);
            self::assertEquals('6097400', $videoData['id']);
            self::assertArrayHasKey('title', $videoData);
            self::assertEquals('Snowboard Gopro', $videoData['title']);
            self::assertArrayHasKey('type', $videoData);
            self::assertEquals('vimeo', $videoData['type']);
        } else {
            self::assertArrayHasKey('id', $videoData);
            self::assertEquals('unknown', $videoData['id']);
            self::assertArrayHasKey('title', $videoData);
            self::assertEquals('Unknown video title', $videoData['title']);
            self::assertArrayHasKey('type', $videoData);
            self::assertEquals('unknown', $videoData['type']);
        }
    }

    /**
     * @dataProvider getUnknownUrl
     *
     * @param string $url
     * @param bool   $isValidUrl
     *
     * @throws JsonException
     */
    public function testGetUnknownVideoData(string $url, bool $isValidUrl): void
    {
        $videoData = $this->videoHelper->getVideoData($url);
        if ($isValidUrl) {
            self::assertArrayHasKey('id', $videoData);
            self::assertEquals('unknown', $videoData['id']);
            self::assertArrayHasKey('title', $videoData);
            self::assertEquals('Unknown video title', $videoData['title']);
            self::assertArrayHasKey('type', $videoData);
            self::assertEquals('unknown', $videoData['type']);
        } else {
            self::assertArrayHasKey('id', $videoData);
            self::assertEquals('unknown', $videoData['id']);
            self::assertArrayHasKey('title', $videoData);
            self::assertEquals('Unknown video title', $videoData['title']);
            self::assertArrayHasKey('type', $videoData);
            self::assertEquals('unknown', $videoData['type']);
        }
    }

    /**
     * @return array[]
     */
    public function getYoutubeUrl(): array
    {
        return [
            ['https://www.youtube.com/embed/Z3MohNj9eVo', true],
            ['Z3MohNj9eVo', false],
        ];
    }

    /**
     * @return array[]
     */
    public function getVimeoUrl(): array
    {
        return [
            ['https://player.vimeo.com/video/6097400', true],
            ['6097400', false],
        ];
    }

    /**
     * @return array[]
     */
    public function getUnknownUrl(): array
    {
        return [
            ['https://www.dailymotion.com/video/x2ny51c', true],
            ['x2ny51c', false],
        ];
    }
}
