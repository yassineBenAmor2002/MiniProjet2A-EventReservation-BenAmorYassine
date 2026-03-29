<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\WebauthnCredentialRepository;

class PasskeyAuthService
{
    public function __construct(
        private WebauthnCredentialRepository $credentialRepository
    ) {}

    // Génère les options pour l'enregistrement d'un passkey
    public function getRegistrationOptions(User $user): array
    {
        // TODO: implémenter la logique WebAuthn
        return [];
    }

    // Vérifie la réponse lors de l'enregistrement
    public function verifyRegistration(string $response, User $user): void
    {
        // TODO: implémenter la vérification
    }

    // Génère les options pour la connexion
    public function getLoginOptions(): array
    {
        // TODO: implémenter la logique WebAuthn
        return [];
    }

    // Vérifie la réponse lors de la connexion et retourne l'utilisateur
    public function verifyLogin(string $response): User
    {
        // TODO: implémenter la vérification
        throw new \Exception("Méthode non implémentée");
    }
}