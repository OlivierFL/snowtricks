<?php

namespace Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AjaxControllerTest extends WebTestCase
{
    public function testLoadMoreTricksWithDefaultLimit(): void
    {
        $client = static::createClient();
        $client->xmlHttpRequest('GET', '/tricks/load-more/4');

        $response = $client->getResponse()->getContent();
        $response = json_decode($response, true, 512, JSON_THROW_ON_ERROR);

        self::assertResponseIsSuccessful();
        self::isJson();
        self::assertCount(4, $response, 'Load more Tricks endpoint returns 4 Tricks');
    }

    public function testLoadMoreTricksWithCustomLimit(): void
    {
        $client = static::createClient();
        $client->xmlHttpRequest('GET', '/tricks/load-more/4/2');

        $response = $client->getResponse()->getContent();
        $response = json_decode($response, true, 512, JSON_THROW_ON_ERROR);

        self::assertResponseIsSuccessful();
        self::isJson();
        self::assertCount(2, $response, 'Load more Tricks endpoint returns 2 Tricks');
    }

    public function testLoadMoreComments(): void
    {
        $client = static::createClient();
        $client->xmlHttpRequest('GET', '/13/comments/load-more/4');

        $response = $client->getResponse()->getContent();
        $response = json_decode($response, true, 512, JSON_THROW_ON_ERROR);

        self::assertResponseIsSuccessful();
        self::isJson();
        self::assertCount(1, $response, 'Load more Comments endpoint returns 1 Comment');
    }
}
