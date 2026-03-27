<?php

namespace App\Controller\Api;

use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/events')]
class EventController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(EntityManagerInterface $em): JsonResponse
    {
        $events = $em->getRepository(Event::class)->findAll();

        return $this->json($events);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(int $id, EntityManagerInterface $em): JsonResponse
    {
        $event = $em->getRepository(Event::class)->find($id);

        if (!$event) {
            return $this->json(['error' => 'Event non trouvé'], 404);
        }

        return $this->json($event);
    }
}