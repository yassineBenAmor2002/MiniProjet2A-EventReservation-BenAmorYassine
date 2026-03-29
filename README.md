# Mini Projet : Application Web de Gestion de Réservations d’Événements

## Description
Application web Symfony pour gérer les réservations d’événements avec authentification sécurisée **JWT + Passkeys**.
- **Utilisateurs** : Consultation des événements et réservation en ligne.
- **Administrateur** : Gestion complète (CRUD) des événements et consultation des réservations via une interface sécurisée.

Projet réalisé dans le cadre du module FIA3-GL, ISSAT Sousse, Année universitaire 2025-2026.

## Fonctionnalités
- **Authentification forte** : Intégration de WebAuthn (Passkeys) pour les utilisateurs.
- **Autorisation stateless** : Utilisation de LexikJWTAuthenticationBundle avec Refresh Tokens.
- **Gestion Admin** : Interface d'administration protégée par login/password classique (ROLE_ADMIN).
- **CRUD Événements** : Création, modification, suppression et affichage des événements.
- **Réservations** : Formulaire de réservation avec confirmation par email.
- **Tests** : Suite de tests PHPUnit pour l'API et la sécurité.

## Installation

### 1. Prérequis
- PHP 8.2+
- Composer
- MySQL 8.0+
- OpenSSL (pour les clés JWT)

### 2. Configuration locale
1. **Cloner le dépôt**
   ```bash
   git clone <URL_DU_DEPOT>
   cd event-reservation
   ```
2. **Installer les dépendances**
   ```bash
   composer install
   ```
3. **Générer les clés JWT**
   ```bash
   php bin/console lexik:jwt:generate-keypair
   ```
4. **Configurer la base de données**
   Modifier le fichier `.env.local` :
   ```env
   DATABASE_URL="mysql://root:@127.0.0.1:3306/event_db?serverVersion=8.0.32&charset=utf8mb4"
   APP_DOMAIN=localhost
   WEBAUTHN_RP_NAME="Event Reservation"
   ```
5. **Initialiser la base de données**
   ```bash
   php bin/console doctrine:database:create
   php bin/console doctrine:schema:update --force
   ```
6. **Créer un compte administrateur**
   ```bash
   php bin/console app:admin:create admin admin123
   ```

### 3. Utilisation de Docker
Le projet inclut une configuration Docker (PHP-FPM, Nginx, MySQL).
```bash
docker-compose up -d --build
```
L'application sera accessible sur `http://localhost:8080`.

## Tests
Lancer la suite de tests avec PHPUnit :
```bash
php bin/phpunit
```

## Structure du Projet
- `src/Entity` : Modèles de données (User, Admin, Event, Reservation, WebauthnCredential).
- `src/Controller` : Logique de l'application (API Auth, Admin CRUD, Réservations).
- `src/Service` : Services métier (PasskeyAuthService pour WebAuthn).
- `templates/` : Vues Twig pour l'interface d'administration.
- `public/js` : Scripts frontend pour la gestion des Passkeys.