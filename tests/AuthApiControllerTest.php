<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AuthApiControllerTest extends WebTestCase
{
    public function testRegisterOptions(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/auth/register/options');

        $this->assertResponseIsSuccessful(); // Vérifie le 2xx
        $this->assertJson($client->getResponse()->getContent()); // Vérifie que c'est du JSON

        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('roles', $data);
    }
}