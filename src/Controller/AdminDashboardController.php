<?php

namespace App\Controller;

use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class AdminDashboardController extends AbstractController
{
    #[Route('/', name: 'admin_dashboard')]
    public function index(EntityManagerInterface $em): Response
    {
        $events = $em->getRepository(Event::class)->findAll();
        return $this->render('admin/dashboard.html.twig', [
            'events' => $events
        ]);
    }

    #[Route('/event/{id}/reservations', name: 'admin_event_reservations')]
    public function viewReservations(Event $event): Response
    {
        $reservations = $event->getReservations(); // Relation OneToMany Event -> Reservations
        return $this->render('admin/event_reservations.html.twig', [
            'event' => $event,
            'reservations' => $reservations
        ]);
    }
}