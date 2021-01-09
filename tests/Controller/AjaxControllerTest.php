<?php

namespace Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AjaxControllerTest extends WebTestCase
{
    /**
     * @dataProvider getTricksList
     *
     * @param $tricks
     */
    public function testLoadMoreTricks($tricks): void
    {
        $client = static::createClient();
        $client->xmlHttpRequest('GET', '/tricks/load-more/4');

        $response = $client->getResponse()->getContent();

        self::assertResponseIsSuccessful();
        self::assertStringContainsString($tricks, $response);
    }

    public function getTricksList(): array
    {
        return [
            ['id' => 4,
             'name' => 'Sad',
             'description' => 'Saisie de la carre backside de la planche, entre les deux pieds, avec la main avant',
             'slug' => 'sad',
             'comments' => [],
             'medias' => [
                 'id' => 4,
                 'url' => 'snowboard_trick_03.jpg',
                 'altText' => 'sad trick',
                 'trick' => 4,
                 'createdAt' => '2020-12-20T15:47:36+01:00',
                 'updatedAt' => '2020-12-20T15:47:36+01:00',
             ],
             'category' => [
                 'id' => 1,
                 'name' => 'grab',
                 'tricks' => [
                     'id' => 1,
                     'name' => 'Mute',
                     'description' => 'Saisie de la carre frontside de la planche entre les deux pieds avec la main avant',
                     'slug' => 'mute',
                     'comments' => [],
                     'medias' => [
                         'id' => 1,
                         'url' => 'trick_example.jpg',
                         'altText' => 'grab',
                         'trick' => 1,
                         'createdAt' => '2020-12-20T11:36:57+01:00',
                         'updatedAt' => '2020-12-20T11:36:57+01:00',
                     ],
                     'category' => 1,
                     'createdAt' => '2020-12-20T11:15:16+01:00',
                     'updatedAt' => '2020-12-20T11:15:16+01:00',
                 ], [
                     'id' => 5,
                     'name' => 'Indy',
                     'description' => 'Saisie de la carre frontside de la planche, entre les deux pieds, avec la main arrière',
                     'slug' => 'indy',
                     'comments' => [],
                     'medias' => [],
                     'category' => 1,
                     'createdAt' => '2020-12-27T11:01:22+01:00',
                     'updatedAt' => '2020-12-27T11:01:22+01:00',
                 ],
                 'createdAt' => '2020-12-20T11:10:53+01:00',
                 'updatedAt' => '2020-12-20T11:10:53+01:00',
                 '__initializer__' => null,
                 '__cloner__' => null,
                 '__isInitialized__' => true,
             ],
             'createdAt' => '2020-12-20T11:39:43+01:00',
             'updatedAt' => '2020-12-20T11:39:43+01:00',
            ],
            [
                'id' => 3,
                'name' => 'Backflip',
                'description' => 'Rotation verticale en arrière',
                'slug' => 'backflip',
                'comments' => [],
                'medias' => [
                    'id' => 3,
                    'url' => 'snowboard_trick_02.jpg',
                    'altText' => 'backflip',
                    'trick' => 3,
                    'createdAt' => '2020-12-20T15:47:10+01:00',
                    'updatedAt' => '2020-12-20T15:47:10+01:00',
                ],
                'category' => [
                    'id' => 3,
                    'name' => 'flip',
                    'tricks' => [
                        'id' => 7,
                        'name' => 'Frontflip',
                        'description' => 'Rotation en avant',
                        'slug' => 'frontflip',
                        'comments' => [],
                        'medias' => [],
                        'category' => 3,
                        'createdAt' => '2020-12-27T11:03:53+01:00',
                        'updatedAt' => '2020-12-27T11:03:53+01:00',
                    ],
                    'createdAt' => '2020-12-20T11:11:51+01:00',
                    'updatedAt' => '2020-12-20T11:11:51+01:00',
                    '__initializer__' => null,
                    '__cloner__' => null,
                    '__isInitialized__' => true,
                ],
                'createdAt' => '2020-12-20T11:17:28+01:00',
                'updatedAt' => '2020-12-20T11:17:28+01:00',
            ], [
                'id' => 2,
                'name' => '360',
                'description' => 'Un tour complet',
                'slug' => '360',
                'comments' => [],
                'medias' => [
                    'id' => 2,
                    'url' => 'snowboard_trick.jpg',
                    'altText' => 'rotation 360',
                    'trick' => 2,
                    'createdAt' => '2020-12-20T15:47:10+01:00',
                    'updatedAt' => '2020-12-20T15:47:10+01:00',
                ],
                'category' => [
                    'id' => 2,
                    'name' => 'rotation',
                    'tricks' => [
                        2,
                        'id' => 6,
                        'name' => '540',
                        'description' => 'Un tour et demi',
                        'slug' => '540',
                        'comments' => [],
                        'medias' => [],
                        'category' => 2,
                        'createdAt' => '2020-12-27T11:01:22+01:00',
                        'updatedAt' => '2020-12-27T11:01:22+01:00',
                    ],
                    'createdAt' => '2020-12-20T11:10:53+01:00',
                    'updatedAt' => '2020-12-20T11:10:53+01:00',
                    '__initializer__' => null,
                    '__cloner__' => null,
                    '__isInitialized__' => true,
                ],
                'createdAt' => '2020-12-20T11:16:08+01:00',
                'updatedAt' => '2020-12-20T11:16:08+01:00',
            ], [
                'id' => 1,
                'name' => 'Mute',
                'description' => 'Saisie de la carre frontside de la planche entre les deux pieds avec la main avant',
                'slug' => 'mute',
                'comments' => [],
                'medias' => [
                    'id' => 1,
                    'url' => 'trick_example.jpg',
                    'altText' => 'grab',
                    'trick' => 1,
                    'createdAt' => '2020-12-20T11:36:57+01:00',
                    'updatedAt' => '2020-12-20T11:36:57+01:00',
                ],
                'category' => [
                    'id' => 1,
                    'name' => 'grab',
                    'tricks' => [
                        'id' => 4,
                        'name' => 'Sad',
                        'description' => 'Saisie de la carre backside de la planche, entre les deux pieds, avec la main avant',
                        'slug' => 'sad',
                        'comments' => [],
                        'medias' => [
                            'id' => 4,
                            'url' => 'snowboard_trick_03.jpg',
                            'altText' => 'sad trick',
                            'trick' => 4,
                            'createdAt' => '2020-12-20T15:47:36+01:00',
                            'updatedAt' => '2020-12-20T15:47:36+01:00',
                        ],
                        'category' => 1,
                        'createdAt' => '2020-12-20T11:39:43+01:00',
                        'updatedAt' => '2020-12-20T11:39:43+01:00',
                    ], [
                        'id' => 5,
                        'name' => 'Indy',
                        'description' => 'Saisie de la carre frontside de la planche, entre les deux pieds, avec la main arrière',
                        'slug' => 'indy',
                        'comments' => [],
                        'medias' => [],
                        'category' => 1,
                        'createdAt' => '2020-12-27T11:01:22+01:00',
                        'updatedAt' => '2020-12-27T11:01:22+01:00',
                    ],
                    'createdAt' => '2020-12-20T11:10:53+01:00',
                    'updatedAt' => '2020-12-20T11:10:53+01:00',
                    '__initializer__' => null,
                    '__cloner__' => null,
                    '__isInitialized__' => true,
                ],
                'createdAt' => '2020-12-20T11:15:16+01:00',
                'updatedAt' => '2020-12-20T11:15:16+01:00',
            ],
        ];
    }
}
