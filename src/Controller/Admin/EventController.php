<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/event')]
#[IsGranted('ROLE_ADMIN')]
class EventController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(EntityManagerInterface $em): JsonResponse
    {
        $events = $em->getRepository(Event::class)->findAll();
        return $this->json($events);
    }

    #[Route('', methods: ['POST'])]
    public function create(EntityManagerInterface $em): JsonResponse
    {
        $event = new Event();
        $event->setTitle("Test Event");
        $event->setDescription("Description...");
        $event->setLocation("Sousse");
        $event->setSeats(100);
        $event->setDate(new \DateTime());

        $em->persist($event);
        $em->flush();

        return $this->json(['message' => 'Event créé']);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(int $id, EntityManagerInterface $em): JsonResponse
    {
        $event = $em->getRepository(Event::class)->find($id);

        if (!$event) {
            return $this->json(['error' => 'Event non trouvé'], 404);
        }

        $event->setTitle("Updated Event");
        $em->flush();

        return $this->json(['message' => 'Event modifié']);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(int $id, EntityManagerInterface $em): JsonResponse
    {
        $event = $em->getRepository(Event::class)->find($id);

        if (!$event) {
            return $this->json(['error' => 'Event non trouvé'], 404);
        }

        $em->remove($event);
        $em->flush();

        return $this->json(['message' => 'Event supprimé']);
    }
}