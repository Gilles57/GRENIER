<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230126145433 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE film ADD COLUMN extension VARCHAR(5) DEFAULT NULL');
        $this->addSql('ALTER TABLE film ADD COLUMN release_date DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__film AS SELECT id, titre, titre_original, annee_sortie, media, vu, a_garder, coup_de_coeur, code_tmbd, description, created_at, updated_at FROM film');
        $this->addSql('DROP TABLE film');
        $this->addSql('CREATE TABLE film (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, titre_original VARCHAR(255) DEFAULT NULL, annee_sortie INTEGER DEFAULT NULL, media VARCHAR(255) DEFAULT NULL, vu BOOLEAN NOT NULL, a_garder BOOLEAN NOT NULL, coup_de_coeur BOOLEAN NOT NULL, code_tmbd INTEGER DEFAULT NULL, description CLOB DEFAULT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('INSERT INTO film (id, titre, titre_original, annee_sortie, media, vu, a_garder, coup_de_coeur, code_tmbd, description, created_at, updated_at) SELECT id, titre, titre_original, annee_sortie, media, vu, a_garder, coup_de_coeur, code_tmbd, description, created_at, updated_at FROM __temp__film');
        $this->addSql('DROP TABLE __temp__film');
    }
}
