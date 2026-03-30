<?php

namespace App\Controller;

use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(EntityManagerInterface $em): Response
    {
        $events = $em->getRepository(Event::class)->findAll();

        return $this->render('home/index.html.twig', [
            'events' => $events,
        ]);
    }

    #[Route('/event/{id}', name: 'event_show')]
    public function show(Event $event): Response
    {
        return $this->render('home/show.html.twig', [
            'event' => $event,
        ]);
    }
}
