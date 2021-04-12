<?php

namespace Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserProfileControllerTest extends WebTestCase
{
    public function testUserProfilePageUserNotConnected(): void
    {
        $client = static::createClient();
        $client->request('GET', '/profil/');
        $client->followRedirect();

        self::assertResponseIsSuccessful();
        self::assertRouteSame('app_login', [], 'User is redirected to login page when accessing profile page without being connected');
    }

    public function testUserProfilePage(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('admin@example.com');
        $client->loginUser($testUser);
        $client->request('GET', '/profil/');

        self::assertResponseIsSuccessful();
        self::assertRouteSame('profile_index', [], 'Connected User can access profile page');
    }

    public function testUserProfileTricksPage(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('admin@example.com');
        $client->loginUser($testUser);
        $client->request('GET', '/profil/tricks');

        self::assertResponseIsSuccessful();
        self::assertRouteSame('profile_tricks', [], 'Connected User can access profile Tricks page');
    }

    public function testUserProfileCommentsPage(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('admin@example.com');
        $client->loginUser($testUser);
        $client->request('GET', '/profil/comments');

        self::assertResponseIsSuccessful();
        self::assertRouteSame('profile_comments', [], 'Connected User can access profile Comments page');
    }

    public function testUpdateUserData(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('admin@example.com');
        $client->loginUser($testUser);
        $crawler = $client->request('GET', '/profil/');

        $buttonCrawlerNode = $crawler->selectButton('Modifier mon profil');
        $form = $buttonCrawlerNode->form();
        $form['edit_profile_form[username]'] = 'admin2';

        $client->submit($form);
        $client->followRedirect();

        self::assertStringContainsString('Profil mis à jour avec succès', $client->getResponse()->getContent(), 'Username is updated successfully flash message');
        self::assertRouteSame('profile_index');
    }

    public function testUpdateUserDataWithExistingUserName(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('admin@example.com');
        $client->loginUser($testUser);
        $crawler = $client->request('GET', '/profil/');

        $buttonCrawlerNode = $crawler->selectButton('Modifier mon profil');
        $form = $buttonCrawlerNode->form();
        $form['edit_profile_form[username]'] = 'test';

        $client->submit($form);

        self::assertStringContainsString('Le nom d&#039;utilisateur est déjà utilisé', $client->getResponse()->getContent(), 'Existing value for UserName error');
    }
}
