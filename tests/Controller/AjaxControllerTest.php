<?php

namespace Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AjaxControllerTest extends WebTestCase
{
    public function testLoadMoreTricks(): void
    {
        $client = static::createClient();
        $client->xmlHttpRequest('GET', '/tricks/load-more/4');

        self::assertResponseIsSuccessful();
    }
}
