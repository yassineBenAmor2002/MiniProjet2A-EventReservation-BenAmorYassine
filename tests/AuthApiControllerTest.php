<?php

namespace App\Tests;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AuthApiControllerTest extends WebTestCase
{
    public function testRegisterOptions(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/auth/register/options', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode(['email' => 'test@example.com']));

        $this->assertResponseIsSuccessful(); // Vérifie le 2xx
        $this->assertJson($client->getResponse()->getContent()); // Vérifie que c'est du JSON

        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('challenge', $data);
        $this->assertArrayHasKey('rp', $data);
        $this->assertArrayHasKey('user', $data);
    }

    public function testMeEndpointRequiresAuth(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/auth/me');
        $this->assertResponseStatusCodeSame(401);
    }

    public function testMeEndpointWithValidToken(): void
    {
        $client = static::createClient();

        /** @var EntityManagerInterface $em */
        $em = static::getContainer()->get(EntityManagerInterface::class);

        $user = $em->getRepository(User::class)->findOneBy(['email' => 'api-test@example.com']);
        if (!$user) {
            $user = new User();
            $user->setEmail('api-test@example.com');
            $user->setUsername('api-test@example.com');
            $user->setPassword('hashed-placeholder');
            $em->persist($user);
            $em->flush();
        }

        $token = static::getContainer()
            ->get('lexik_jwt_authentication.jwt_manager')
            ->create($user);

        $client->request('GET', '/api/auth/me', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token,
        ]);

        $this->assertResponseIsSuccessful();
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertSame('api-test@example.com', $response['email'] ?? null);
    }
}