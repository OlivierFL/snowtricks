<?php

namespace Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TricksControllerTest extends WebTestCase
{
    public function testTricksPage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/#tricks');

        self::assertResponseIsSuccessful();
        self::assertCount(8, $crawler->filter('.tricks'), 'Default homepage Tricks section contains 8 Tricks');
    }

    public function testTrickDetailPage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/tricks/nose');

        self::assertResponseIsSuccessful();
        self::assertPageTitleSame('Nose - SnowTricks', 'Trick page title is equal to Nose');
        $h1 = $crawler->filter('h1')->first()->text();
        self::assertEquals('Nose', $h1, 'Trick H1 title is equal to Nose');
        self::assertCount(2, $crawler->filter('.comments'), 'Tricks section contains 2 comments by default');
        $notConnectedMessage = $crawler->filter('#comment-message')->text();
        self::assertEquals('Veuillez vous connecter pour poster un commentaire', $notConnectedMessage, 'Page does not display comment form when user is not connected');
    }

    public function testTrickDetailPageWithUserConnected(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('admin@example.com');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/tricks/nose');

        self::assertResponseIsSuccessful();
        self::assertPageTitleSame('Nose - SnowTricks', 'Trick page title is equal to Nose');
        $h1 = $crawler->filter('h1')->first()->text();
        self::assertEquals('Nose', $h1, 'Trick H1 title is equal to Nose');
        self::assertCount(2, $crawler->filter('.comments'), 'Tricks section contains 2 comments by default');
        $commentFormLabel = $crawler->filter('label[for="comment_form_content"]')->text();
        self::assertStringContainsString('Votre commentaire', $commentFormLabel, 'Comment form is available when user is connected');
    }

    public function testCommentIsSubmitted(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('admin@example.com');
        $client->loginUser($testUser);

        $client->request('GET', '/tricks/mute');

        self::assertResponseIsSuccessful();

        $client->submitForm('Soumettre', [
            'comment_form[content]' => 'Commentaire de test',
        ]);

        self::assertStringContainsString('Votre commentaire a été soumis pour validation', $client->getResponse()->getContent(), 'Comment is submitted to moderation flash message');
        self::assertRouteSame('trick_detail');
    }

    public function testCommentIsSubmittedWithEmptyData(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('admin@example.com');
        $client->loginUser($testUser);

        $client->request('GET', '/tricks/mute');

        self::assertResponseIsSuccessful();

        $client->submitForm('Soumettre', [
            'comment_form[content]' => '',
        ]);

        self::assertStringContainsString('Cette valeur ne doit pas être vide.', $client->getResponse()->getContent(), 'Comment form error with empty content');
        self::assertRouteSame('trick_detail');
    }

    public function testGoToNewTrickPageFromHomepage(): void
    {
        $client = static::createClient();

        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('admin@example.com');
        $client->loginUser($testUser);

        $client->request('GET', '/');

        self::assertResponseIsSuccessful();

        $client->clickLink('Ajouter un trick');
        self::assertResponseIsSuccessful();
        self::assertRouteSame('trick_new', [], 'New Trick page is accessible from homepage');
    }

    public function testCreateNewTrickUserNotConnected(): void
    {
        $client = static::createClient();

        $client->request('GET', '/tricks/new');
        $client->followRedirect();

        self::assertResponseIsSuccessful();
        self::assertRouteSame('app_login', [], 'User is redirected to login page when trying to access new Trick page without being connected');
    }

    public function testCreateNewTrickWithMinimumData(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('admin@example.com');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/tricks/new');

        self::assertResponseIsSuccessful();

        $buttonCrawlerNode = $crawler->selectButton('Créer le trick');
        $form = $buttonCrawlerNode->form();
        $form['trick[name]'] = 'Trick';
        $form['trick[category]']->select('3');
        $form['trick[description]'] = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aut ducimus earum eum expedita, illum ipsam necessitatibus nostrum quas rem voluptatem.';

        $client->submit($form);
        $client->followRedirect();

        self::assertStringContainsString('Nouveau trick créé', $client->getResponse()->getContent(), 'Trick is created successfully flash message');
        self::assertRouteSame('trick_detail', [], 'User is redirected to created Trick page after creation');
    }

    public function testCreateNewTrickWithErrors(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('admin@example.com');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/tricks/new');

        self::assertResponseIsSuccessful();

        $buttonCrawlerNode = $crawler->selectButton('Créer le trick');
        $form = $buttonCrawlerNode->form();
        $form['trick[name]'] = '';
        $form['trick[category]']->select('3');
        $form['trick[description]'] = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aut ducimus earum eum expedita, illum ipsam necessitatibus nostrum quas rem voluptatem.';

        $client->submit($form);

        self::assertStringContainsString('Cette valeur ne doit pas être vide.', $client->getResponse()->getContent(), 'Empty value for Trick name error');

        $crawler = $client->request('GET', '/tricks/new');

        self::assertResponseIsSuccessful();

        $buttonCrawlerNode = $crawler->selectButton('Créer le trick');
        $form = $buttonCrawlerNode->form();
        $form['trick[name]'] = 'Trick';
        $form['trick[category]']->select('3');
        $form['trick[description]'] = '';

        $client->submit($form);

        self::assertStringContainsString('Cette valeur ne doit pas être vide.', $client->getResponse()->getContent(), 'Empty value for Trick description error');
    }

    public function testEditTrickBaseDataWithUserNotConnected(): void
    {
        $client = static::createClient();
        $client->request('GET', '/tricks/mute/edit');
        $client->followRedirect();

        self::assertResponseIsSuccessful();
        self::assertRouteSame('app_login', [], 'User is redirected to login page when trying to access Trick edit page without being connected');
    }

    public function testDeleteTrickWithUserNotConnected(): void
    {
        $client = static::createClient();
        $client->request('GET', '/tricks/1/delete');
        $client->followRedirect();

        self::assertResponseIsSuccessful();
        self::assertRouteSame('app_login', [], 'User is redirected to login page when trying to delete Trick without being connected');
    }

    public function testDeleteTrickWithUserNotAuthor(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('test@example.com');
        $client->loginUser($testUser);

        $client->request('GET', '/tricks/1/delete');

        self::assertResponseStatusCodeSame(403, 'User cannot delete Trick if not author');
    }

    public function testDeleteTrickWithUserAuthor(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('admin@example.com');
        $client->loginUser($testUser);

        $client->request('GET', '/tricks/1/delete');
        $client->followRedirect();

        self::assertResponseIsSuccessful();
        self::assertRouteSame('home', [], 'User is redirected to homepage after deleting Trick');
        self::assertStringContainsString('Le trick a été supprimé', $client->getResponse()->getContent(), 'Trick is successfully deleted');
    }
}
