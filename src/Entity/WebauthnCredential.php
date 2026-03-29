<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
class WebauthnCredential
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private Uuid $id;

    #[ORM\Column(length: 255, unique: true, nullable: true)]
    private ?string $credentialId = null;

    #[ORM\Column(type: 'text')]
    private string $credentialData; // JSON de PublicKeyCredentialSource

    #[ORM\Column(length: 255)]
    private string $name; // Nom lisible par l'utilisateur

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column]
    private \DateTimeImmutable $lastUsedAt;

    #[ORM\ManyToOne(inversedBy: 'webauthnCredentials')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function __construct()
    {
        $this->id = Uuid::v4();
        $this->createdAt = new \DateTimeImmutable();
        $this->lastUsedAt = new \DateTimeImmutable();
    }

    public function getId(): Uuid { return $this->id; }

    public function getCredentialId(): ?string { return $this->credentialId; }
    public function setCredentialId(?string $credentialId): self { $this->credentialId = $credentialId; return $this; }

    public function getCredentialData(): string { return $this->credentialData; }
    public function setCredentialData(string $data): self { $this->credentialData = $data; return $this; }

    public function getName(): string { return $this->name; }
    public function setName(string $name): self { $this->name = $name; return $this; }

    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
    public function getLastUsedAt(): \DateTimeImmutable { return $this->lastUsedAt; }
    public function setLastUsedAt(\DateTimeImmutable $date): self { $this->lastUsedAt = $date; return $this; }

    public function getUser(): ?User { return $this->user; }
    public function setUser(?User $user): self { $this->user = $user; return $this; }
}