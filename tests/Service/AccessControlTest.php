<?php

namespace App\Tests;

use App\Repository\UsuarioRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Flex\Response;

class AccessControlTest extends WebTestCase
{
    public function testHomepageWorks(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/home');

        $this->assertResponseIsSuccessful();
        $client->followRedirect();

        $this->assertSelectorTextContains('h2', 'Últimes Novetats');
    }

    /**
     * @param string $url
     * @param int $statusCode
     * @param string $method
     * @return void
     * @dataProvider getAnonymousData
     * @test
     */
    public function testAnonymousAccessControlWorks(string $url, int $statusCode, string $method = 'GET') {
        $client = static::createClient();
        $crawler = $client->request($method, $url);

        $this->assertResponseStatusCodeSame($statusCode);
    }

    public function getAnonymousData():iterable
    {
        yield ['/register', 200];
        yield ['/login', 200];
    }

    public function testAnonymousUsersCannotAccessProfile() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/profile');

        $this->assertResponseStatusCodeSame(302);
    }

    public function testLoggedUserCanAccessProfile() {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UsuarioRepository::class);

        $testUser = $userRepository->findOneByUsername(['username' => 'user']);
        $client->loginUser($testUser);

        $crawler = $client->request(method:'GET', uri:'/profile');

        $this->assertResponseStatusCodeSame(200);
    }

}
