<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\WebauthnCredential;
use App\Repository\WebauthnCredentialRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Webauthn\AuthenticatorAssertionResponseValidator;
use Webauthn\AuthenticatorAttestationResponseValidator;
use Webauthn\PublicKeyCredentialLoader;
use Webauthn\PublicKeyCredentialSource;
use Webauthn\PublicKeyCredentialUserEntity;
use Webauthn\AuthenticatorSelectionCriteria;
use Webauthn\PublicKeyCredentialRpEntity;
use Webauthn\PublicKeyCredentialDescriptor;
use Webauthn\PublicKeyCredentialParameters;

use Webauthn\Bundle\Service\PublicKeyCredentialCreationOptionsFactory;
use Webauthn\Bundle\Service\PublicKeyCredentialRequestOptionsFactory;

class PasskeyAuthService
{
    public function __construct(
        private AuthenticatorAttestationResponseValidator $attestationValidator,
        private AuthenticatorAssertionResponseValidator $assertionValidator,
        private PublicKeyCredentialLoader $publicKeyCredentialLoader,
        private PublicKeyCredentialCreationOptionsFactory $creationOptionsFactory,
        private PublicKeyCredentialRequestOptionsFactory $requestOptionsFactory,
        private RequestStack $requestStack,
        private WebauthnCredentialRepository $credRepo,
        private string $appDomain
    ) {}

    public function getRegistrationOptions(User $user): array
    {
        $userEntity = new PublicKeyCredentialUserEntity(
            $user->getEmail(),
            (string) $user->getId(),
            $user->getEmail()
        );

        $options = $this->creationOptionsFactory->create(
            'default',
            $userEntity
        );

        $this->requestStack->getSession()->set('registration_challenge', base64_encode($options->getChallenge()));

        return $options->jsonSerialize();
    }

    public function verifyRegistration(User $user, array $data): bool
    {
        $challenge = $this->requestStack->getSession()->get('registration_challenge');
        if (!$challenge) {
            throw new \Exception("Challenge d'enregistrement manquant.");
        }

        try {
            $publicKeyCredential = $this->publicKeyCredentialLoader->loadArray($data);
            $response = $publicKeyCredential->getResponse();

            if (!$response instanceof \Webauthn\AuthenticatorAttestationResponse) {
                throw new \Exception("Réponse d'attestation invalide.");
            }

            $userEntity = new PublicKeyCredentialUserEntity($user->getEmail(), (string)$user->getId(), $user->getEmail());
            
            // On recrée les options pour la validation
            $options = $this->creationOptionsFactory->create('default', $userEntity);
            // On force le challenge stocké en session pour la validation
            $reflectedOptions = new \ReflectionClass($options);
            $challengeProp = $reflectedOptions->getProperty('challenge');
            $challengeProp->setAccessible(true);
            $challengeProp->setValue($options, base64_decode($challenge));

            $credentialSource = $this->attestationValidator->check(
                $response,
                $options,
                $this->requestStack->getCurrentRequest()->getHost()
            );

            $credential = new WebauthnCredential();
            $credential->setUser($user);
            $credential->setCredentialId(base64_encode($credentialSource->getPublicKeyCredentialId()));
            $credential->setCredentialData(json_encode($credentialSource));
            $this->credRepo->save($credential, true);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getLoginOptions(User $user): array
    {
        $allowedCredentials = [];
        $credentials = $this->credRepo->findBy(['user' => $user]);
        foreach ($credentials as $cred) {
            $allowedCredentials[] = new PublicKeyCredentialDescriptor(
                PublicKeyCredentialDescriptor::CREDENTIAL_TYPE_PUBLIC_KEY,
                base64_decode($cred->getCredentialId())
            );
        }

        $options = $this->requestOptionsFactory->create(
            'default',
            $allowedCredentials
        );

        $this->requestStack->getSession()->set('login_challenge', base64_encode($options->getChallenge()));

        return $options->jsonSerialize();
    }

    public function verifyLogin(User $user, array $data): bool
    {
        $challenge = $this->requestStack->getSession()->get('login_challenge');
        if (!$challenge) {
            throw new \Exception("Challenge de connexion manquant.");
        }

        try {
            $publicKeyCredential = $this->publicKeyCredentialLoader->loadArray($data);
            $response = $publicKeyCredential->getResponse();

            if (!$response instanceof \Webauthn\AuthenticatorAssertionResponse) {
                throw new \Exception("Réponse d'assertion invalide.");
            }

            $credentialId = base64_encode($publicKeyCredential->getRawId());
            $dbCredential = $this->credRepo->findOneByCredentialId($credentialId);

            if (!$dbCredential || $dbCredential->getUser()->getId() !== $user->getId()) {
                throw new \Exception("Credential inconnu ou utilisateur incorrect.");
            }

            $dbCredential->setLastUsedAt(new \DateTimeImmutable());
            $this->credRepo->save($dbCredential, true);
            $this->requestStack->getSession()->remove('login_challenge');

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function encodeCredentialId(string $credentialId): string
    {
        return rtrim(strtr(base64_encode($credentialId), '+/', '-_'), '=');
    }
}