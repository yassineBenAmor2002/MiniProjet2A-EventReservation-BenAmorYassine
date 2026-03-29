<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class VersionBaseline extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Baseline migration pour synchroniser le metadata storage';
    }

    public function up(Schema $schema): void
    {
        // vide, car la base est déjà à jour
    }

    public function down(Schema $schema): void
    {
        // vide
    }
}