<?php
namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\Event;
use App\Service\ReservationMailer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route; // ✅ corrige l'erreur

class ReservationController extends AbstractController
{
    #[Route('/event/{id}/reserve', name: 'event_reserve', methods: ['POST'])]
    public function reserve(
        Event $event,
        Request $request,
        EntityManagerInterface $em,
        ReservationMailer $reservationMailer
    ): Response {
        $name = trim($request->request->get('name', ''));
        $email = trim($request->request->get('email', ''));
        $phone = trim($request->request->get('phone', ''));

        if (!$name || !$email || !$phone) {
            $this->addFlash('error', 'Tous les champs sont obligatoires.');
            return $this->redirectToRoute('event_show', ['id' => $event->getId()]);
        }

        $reservation = new Reservation();
        $reservation->setEvent($event)
                    ->setName($name)
                    ->setEmail($email)
                    ->setPhone($phone)
                    ->setCreatedAt(new \DateTime());

        $em->persist($reservation);
        $em->flush();

        try {
            $reservationMailer->sendConfirmation($reservation);
            $this->addFlash('success', 'Votre réservation a été confirmée ! Un email de confirmation a été envoyé.');
        } catch (\Exception $e) {
            $this->addFlash('error', 'La réservation a été enregistrée mais le mail n’a pas pu être envoyé.');
        }

        return $this->redirectToRoute('event_show', ['id' => $event->getId()]);
    }
}