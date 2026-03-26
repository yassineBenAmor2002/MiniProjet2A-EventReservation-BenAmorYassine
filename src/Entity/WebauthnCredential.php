<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User; // <- juste l'import, pas de class ici !

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

    #[ORM\Column(type: Types::TEXT)]
    private ?string $credentialData = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $lastUsedAt = null;

    public function getId(): ?int { return $this->id; }
    public function getCredentialId(): string { return $this->credentialId; }
    public function setCredentialId(string $id): self { $this->credentialId = $id; return $this; }
    public function getPublicKey(): string { return $this->publicKey; }
    public function setPublicKey(string $key): self { $this->publicKey = $key; return $this; }
    public function getCounter(): int { return $this->counter; }
    public function setCounter(int $c): self { $this->counter = $c; return $this; }
    public function getUser() { return $this->user; }
    public function setUser($user): self { $this->user = $user; return $this; }

    public function getCredentialData(): ?string
    {
        return $this->credentialData;
    }

    public function setCredentialData(string $credentialData): static
    {
        $this->credentialData = $credentialData;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getLastUsedAt(): ?\DateTimeImmutable
    {
        return $this->lastUsedAt;
    }

    public function setLastUsedAt(\DateTimeImmutable $lastUsedAt): static
    {
        $this->lastUsedAt = $lastUsedAt;

        return $this;
    }
}