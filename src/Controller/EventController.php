<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/events')]
class EventController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Liste des événements'
        ]);
    }

    #[Route('', methods: ['POST'])]
    public function create(): JsonResponse
    {
        return $this->json([
            'message' => 'Event créé'
        ]);
    }
}