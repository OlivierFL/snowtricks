<?php

namespace Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerTest extends WebTestCase
{
    public function testAdminPageUserNotConnected(): void
    {
        $client = static::createClient();
        $client->request('GET', '/admin/dashboard');
        $client->followRedirect();

        self::assertResponseIsSuccessful();
        self::assertRouteSame('app_login', [], 'User is redirected to login page when accessing admin dashboard without being connected');
    }

    public function testAdminPageUserNotHasRoleAdmin(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('test@example.com');
        $client->loginUser($testUser);
        $client->request('GET', '/admin/dashboard');

        self::assertResponseStatusCodeSame(403, 'User without role admin can not access admin dashboard');
    }

    public function testAdminPage(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('admin@example.com');
        $client->loginUser($testUser);
        $client->request('GET', '/admin/dashboard');

        self::assertResponseIsSuccessful();
        self::assertRouteSame('admin_dashboard', [], 'Connected User can access admin dashboard');
    }

    public function testAdminTricksPage(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('admin@example.com');
        $client->loginUser($testUser);
        $client->request('GET', '/admin/tricks');

        self::assertResponseIsSuccessful();
        self::assertRouteSame('admin_tricks', [], 'Connected User can access admin Tricks page');
    }

    public function testAdminCommentsPage(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('admin@example.com');
        $client->loginUser($testUser);
        $client->request('GET', '/admin/comments');

        self::assertResponseIsSuccessful();
        self::assertRouteSame('admin_comments', [], 'Connected User can access admin Comments page');
    }

    public function testModerateComment(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('admin@example.com');
        $client->loginUser($testUser);

        // Publish Comment
        $client->request('POST', '/admin/moderate-comment/1', ['id' => 2]);
        $client->followRedirect();

        self::assertResponseIsSuccessful();
        self::assertStringContainsString('Le commentaire a été publié', $client->getResponse()->getContent(), 'Comment is published');

        // Moderate Comment
        $client->request('POST', '/admin/moderate-comment/0', ['id' => 2]);
        $client->followRedirect();

        self::assertResponseIsSuccessful();
        self::assertStringContainsString('Le commentaire a été modéré', $client->getResponse()->getContent(), 'Comment is published');
    }
}
