<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;

class WebAuthnService
{
    private $session;

    public function __construct(RequestStack $requestStack)
    {
        $this->session = $requestStack->getSession();
    }

    // Générer challenge
    public function generateChallenge(): string
    {
        $challenge = base64_encode(random_bytes(32));
        $this->session->set('webauthn_challenge', $challenge);

        return $challenge;
    }

    // Vérifier challenge (simulation simple)
    public function verifyChallenge(string $challenge): bool
    {
        return $this->session->get('webauthn_challenge') === $challenge;
    }
    public function storeCredential(array $data): bool
{
    $credentialId = $data['credentialId'] ?? null;
    $publicKey = $data['publicKey'] ?? null;

    if (!$credentialId || !$publicKey) {
        return false;
    }

    $this->session->set('webauthn_user', [
        'credentialId' => $credentialId,
        'publicKey' => $publicKey
    ]);

    return true;
}
}