<?php

namespace App\Controller;

use App\Service\WebAuthnService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use App\Entity\User;

class WebAuthnController extends AbstractController
{
    #[Route('/api/webauthn/register/options', methods: ['POST'])]
    public function registerOptions(WebAuthnService $service): JsonResponse
    {
        return $this->json([
            'challenge' => $service->generateChallenge(),
            'rp' => ['name' => 'Event App'],
        ]);
    }

    #[Route('/api/webauthn/register/verify', methods: ['POST'])]
    public function registerVerify(
        Request $request,
        WebAuthnService $service,
        JWTTokenManagerInterface $jwtManager
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if ($service->verifyChallenge($data['challenge'] ?? '') && $service->storeCredential($data)) {

            //Simulation utilisateur (version simple validée)
            $user = new User();
            $user->setUsername('test@test.com');

            $token = $jwtManager->create($user);

            return $this->json([
                'success' => true,
                'token' => $token
            ]);
        }

        return $this->json(['error' => 'Invalid'], 400);
    }

    #[Route('/api/webauthn/login/options', methods: ['POST'])]
    public function loginOptions(WebAuthnService $service): JsonResponse
    {
        return $this->json([
            'challenge' => $service->generateChallenge(),
            'rp' => ['name' => 'Event App'],
        ]);
    }

    #[Route('/api/webauthn/login/verify', methods: ['POST'])]
    public function loginVerify(
        Request $request,
        WebAuthnService $service,
        JWTTokenManagerInterface $jwtManager
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if ($service->verifyChallenge($data['challenge'] ?? '')) {

            $user = new User();
            $user->setUsername('test@test.com');

            $token = $jwtManager->create($user);

            return $this->json([
                'success' => true,
                'token' => $token
            ]);
        }

        return $this->json(['error' => 'Invalid challenge'], 400);
    }
}