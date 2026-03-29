<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class WebauthnCredential
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: "text")]
    private string $credentialId;

    #[ORM\Column(type: "text")]
    private string $publicKey;

    #[ORM\Column]
    private int $counter = 0;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private $user;

    public function getId(): ?int { return $this->id; }
    public function getCredentialId(): string { return $this->credentialId; }
    public function setCredentialId(string $id): self { $this->credentialId = $id; return $this; }
    public function getPublicKey(): string { return $this->publicKey; }
    public function setPublicKey(string $key): self { $this->publicKey = $key; return $this; }
    public function getCounter(): int { return $this->counter; }
    public function setCounter(int $c): self { $this->counter = $c; return $this; }
    public function getUser() { return $this->user; }
    public function setUser($user): self { $this->user = $user; return $this; }
}