<?php

namespace App\Tests;

use App\Entity\Usuario;
use App\Repository\UsuarioRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher;
use Symfony\Component\Security\Core\User\UserInterface;

class AccessControlTest extends WebTestCase
{
    public function testAnonymousCannotAccessProfile(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/profile');

        # En aquest cas comprovem que si un usuari anònim intenta accedir ha de redirigir-lo
        # a la pàgina d'inici de sessió
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }

    public function testAutenthicatedUsersCanSaveVinil():void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UsuarioRepository::class);

        $testUser = $userRepository->findOneBy(['username' => 'user']);

        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/vinils/1/save');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    /**
     * @param string $url
     * @param int $status
     * @param string $method
     * @return void
     * @dataProvider getUrlsForAnonymousUsers
     */
    public function testAnonymousAccessControl(string $url, int $status, string $method='GET')
    {
        $client = static::createClient();
        $crawler = $client->request($method,$url);

        $this->assertResponseStatusCodeSame($status);
    }
    public function getUrlsForAnonymousUsers(): iterable
    {
        yield['/home', Response::HTTP_OK];
        yield['/login', Response::HTTP_OK];
        yield['/register', Response::HTTP_OK];
        yield['/logout', Response::HTTP_FOUND];
        yield['/profile', Response::HTTP_FOUND];
        yield['/backoffice', Response::HTTP_FOUND];
    }
}
