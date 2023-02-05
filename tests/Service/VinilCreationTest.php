<?php

namespace App\Tests\Service;

use App\Repository\UsuarioRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class VinilCreationTest extends WebTestCase
{
    public function vinilCreation(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UsuarioRepository::class);

        $testUser = $userRepository->findOneBy(['username' => 'user']);
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/backoffice/vinils/new');

        $buttonCrawlerNode = $crawler->selectButton('Registrar');

        $form = $buttonCrawlerNode->form();

        $form['name']->setValue('Vinilo de prueba');
        $form['price']->setValue(23);
        $form['artista']->setValue('Cicatriz');
        $form['description']->setValue('Vinilo de prueba para testing');

        $client->submit($form);

        $this->assertResponseRedirects();
        $client->followRedirect();
    }

    public function errorIsShown(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UsuarioRepository::class);

        $testUser = $userRepository->findOneBy(['username' => 'user']);
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/backoffice/vinils/new');

        $buttonCrawlerNode = $crawler->selectButton('Registrar');

        $form = $buttonCrawlerNode->form();
        $client->submit($form);

        $this->assertSelectorExists('.invalid-feedback');
    }
}
