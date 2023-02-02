<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230202113633 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(100) NOT NULL, forename VARCHAR(100) NOT NULL, name VARCHAR(100) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(25) NOT NULL, is_verified BOOLEAN NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
        $this->addSql('DROP TABLE users');
        $this->addSql('CREATE TEMPORARY TABLE __temp__film AS SELECT id, titre, titre_original, annee_sortie, media, vu, a_garder, coup_de_coeur, created_at, updated_at, code_tmbd, description, extension, release_date FROM film');
        $this->addSql('DROP TABLE film');
        $this->addSql('CREATE TABLE film (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, titre_original VARCHAR(255) DEFAULT NULL, annee_sortie INTEGER DEFAULT NULL, media VARCHAR(255) DEFAULT NULL, vu BOOLEAN NOT NULL, a_garder BOOLEAN NOT NULL, coup_de_coeur BOOLEAN NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , code_tmbd VARCHAR(255) DEFAULT NULL, description CLOB DEFAULT NULL, extension VARCHAR(5) DEFAULT NULL, release_date DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('INSERT INTO film (id, titre, titre_original, annee_sortie, media, vu, a_garder, coup_de_coeur, created_at, updated_at, code_tmbd, description, extension, release_date) SELECT id, titre, titre_original, annee_sortie, media, vu, a_garder, coup_de_coeur, created_at, updated_at, code_tmbd, description, extension, release_date FROM __temp__film');
        $this->addSql('DROP TABLE __temp__film');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE users (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(100) NOT NULL COLLATE "BINARY", forename VARCHAR(100) NOT NULL COLLATE "BINARY", name VARCHAR(100) NOT NULL COLLATE "BINARY", roles CLOB NOT NULL COLLATE "BINARY" --(DC2Type:json)
        , password VARCHAR(25) NOT NULL COLLATE "BINARY", created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , is_verified BOOLEAN NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9E7927C74 ON users (email)');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TEMPORARY TABLE __temp__film AS SELECT id, titre, titre_original, annee_sortie, media, vu, a_garder, coup_de_coeur, code_tmbd, description, extension, release_date, created_at, updated_at FROM film');
        $this->addSql('DROP TABLE film');
        $this->addSql('CREATE TABLE film (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, titre_original VARCHAR(255) DEFAULT NULL, annee_sortie INTEGER DEFAULT NULL, media VARCHAR(255) DEFAULT NULL, vu BOOLEAN NOT NULL, a_garder BOOLEAN NOT NULL, coup_de_coeur BOOLEAN NOT NULL, code_tmbd INTEGER DEFAULT NULL, description CLOB DEFAULT NULL, extension VARCHAR(5) DEFAULT NULL, release_date DATETIME DEFAULT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('INSERT INTO film (id, titre, titre_original, annee_sortie, media, vu, a_garder, coup_de_coeur, code_tmbd, description, extension, release_date, created_at, updated_at) SELECT id, titre, titre_original, annee_sortie, media, vu, a_garder, coup_de_coeur, code_tmbd, description, extension, release_date, created_at, updated_at FROM __temp__film');
        $this->addSql('DROP TABLE __temp__film');
    }
}
