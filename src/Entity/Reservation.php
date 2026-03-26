<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    private ?Event $reservations = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReservations(): ?Event
    {
        return $this->reservations;
    }

    public function setReservations(?Event $reservations): static
    {
        $this->reservations = $reservations;

        return $this;
    }
}
