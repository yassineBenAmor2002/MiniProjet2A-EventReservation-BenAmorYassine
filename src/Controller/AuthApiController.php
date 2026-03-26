<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/auth')]
class AuthApiController extends AbstractController
{
    #[Route('/register/options', methods: ['POST'])]
    public function registerOptions(): JsonResponse
    {
        return $this->json(['message' => 'register options']);
    }

    #[Route('/register/verify', methods: ['POST'])]
    public function registerVerify(): JsonResponse
    {
        return $this->json(['message' => 'register verify']);
    }

    #[Route('/login/options', methods: ['POST'])]
    public function loginOptions(): JsonResponse
    {
        return $this->json(['message' => 'login options']);
    }

    #[Route('/login/verify', methods: ['POST'])]
    public function loginVerify(): JsonResponse
    {
        return $this->json(['message' => 'login verify']);
    }

    #[Route('/me', methods: ['GET'])]
    public function me(): JsonResponse
    {
        return $this->json([
            'user' => $this->getUser()
        ]);
    }
}