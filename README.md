# Mini Projet : Application Web de Gestion de Réservations d’Événements

## Description
Application web Symfony pour gérer les réservations d’événements avec authentification sécurisée JWT + Passkeys.  
- Les utilisateurs peuvent consulter les événements et réserver en ligne.  
- L’administrateur peut gérer les événements et les réservations via une interface sécurisée.  
Mini-projet FIA3-GL, ISSAT Sousse, Année universitaire 2025-2026.

## Technologies utilisées
- **Symfony 7.4**
- **JWT (LexikJWT + Refresh Tokens)** pour l’autorisation stateless
- **Passkeys / WebAuthn** pour une authentification forte
- **Docker** (conteneurisation pour dev et prod)
- PHP 8.1+, Composer, MySQL/PostgreSQL

## Installation

1. **Cloner le projet**
```bash
git clone <URL_DU_DEPOT>
cd MiniProjet2A-EventReservation