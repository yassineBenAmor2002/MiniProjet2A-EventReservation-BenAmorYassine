<?php
namespace App\Service;

use App\Entity\Reservation;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class ReservationMailer
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendConfirmation(Reservation $reservation): void
    {
        $event = $reservation->getEvent();

        $email = (new Email())
            ->from('no-reply@monapp.com')
            ->to($reservation->getEmail())
            ->subject('Confirmation de réservation')
            ->html(sprintf(
                '<h3>Bonjour %s,</h3>
                 <p>Votre réservation pour l\'événement <strong>%s</strong> a été confirmée.</p>
                 <p>Date : %s</p>
                 <p>Merci !</p>',
                htmlspecialchars($reservation->getName()),
                htmlspecialchars($event->getEvent()), // ← ici : utiliser getEvent()
                $event->getDate()->format('d/m/Y H:i')
            ));

        $this->mailer->send($email);
    }
}