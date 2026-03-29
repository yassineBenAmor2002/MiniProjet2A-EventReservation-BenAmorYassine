<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/events')]
#[IsGranted('ROLE_ADMIN')]
class AdminEventCrudController extends AbstractController
{
    #[Route('/new', name: 'admin_event_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $event = new Event();

        if ($request->isMethod('POST')) {
            $title = trim((string) $request->request->get('title', ''));
            $description = trim((string) $request->request->get('description', ''));
            $location = trim((string) $request->request->get('location', ''));
            $seats = (int) $request->request->get('seats', 0);
            $image = trim((string) $request->request->get('image', ''));
            $dateStr = trim((string) $request->request->get('date', ''));

            $date = $dateStr ? \DateTime::createFromFormat('Y-m-d\TH:i', $dateStr) : false;

            if (!$title || !$description || !$location || $seats <= 0 || !$date) {
                $this->addFlash('error', 'Champs invalides.');
            } else {
                $event->setTitle($title);
                $event->setDescription($description);
                $event->setLocation($location);
                $event->setSeats($seats);
                $event->setDate($date);
                $event->setImage($image ?: null);

                $em->persist($event);
                $em->flush();

                $this->addFlash('success', 'Événement créé.');
                return $this->redirectToRoute('admin_dashboard');
            }
        }

        return $this->render('admin/event_form.html.twig', [
            'event' => $event,
            'mode' => 'create',
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_event_edit', methods: ['GET', 'POST'])]
    public function edit(Event $event, Request $request, EntityManagerInterface $em): Response
    {
        if ($request->isMethod('POST')) {
            $title = trim((string) $request->request->get('title', ''));
            $description = trim((string) $request->request->get('description', ''));
            $location = trim((string) $request->request->get('location', ''));
            $seats = (int) $request->request->get('seats', 0);
            $image = trim((string) $request->request->get('image', ''));
            $dateStr = trim((string) $request->request->get('date', ''));

            $date = $dateStr ? \DateTime::createFromFormat('Y-m-d\TH:i', $dateStr) : false;

            if (!$title || !$description || !$location || $seats <= 0 || !$date) {
                $this->addFlash('error', 'Champs invalides.');
            } else {
                $event->setTitle($title);
                $event->setDescription($description);
                $event->setLocation($location);
                $event->setSeats($seats);
                $event->setDate($date);
                $event->setImage($image ?: null);

                $em->flush();

                $this->addFlash('success', 'Événement mis à jour.');
                return $this->redirectToRoute('admin_dashboard');
            }
        }

        return $this->render('admin/event_form.html.twig', [
            'event' => $event,
            'mode' => 'edit',
        ]);
    }

    #[Route('/{id}/delete', name: 'admin_event_delete', methods: ['POST'])]
    public function delete(Event $event, Request $request, EntityManagerInterface $em): Response
    {
        if (!$this->isCsrfTokenValid('delete_event_' . $event->getId(), (string) $request->request->get('_token'))) {
            throw $this->createAccessDeniedException();
        }

        $em->remove($event);
        $em->flush();

        $this->addFlash('success', 'Événement supprimé.');
        return $this->redirectToRoute('admin_dashboard');
    }
}
