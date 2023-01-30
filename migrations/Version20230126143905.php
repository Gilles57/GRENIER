<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230126143905 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE film_genre (film_id INTEGER NOT NULL, genre_id INTEGER NOT NULL, PRIMARY KEY(film_id, genre_id), CONSTRAINT FK_1A3CCDA8567F5183 FOREIGN KEY (film_id) REFERENCES film (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_1A3CCDA84296D31F FOREIGN KEY (genre_id) REFERENCES genre (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_1A3CCDA8567F5183 ON film_genre (film_id)');
        $this->addSql('CREATE INDEX IDX_1A3CCDA84296D31F ON film_genre (genre_id)');
        $this->addSql('CREATE TABLE film_version (film_id INTEGER NOT NULL, version_id INTEGER NOT NULL, PRIMARY KEY(film_id, version_id), CONSTRAINT FK_5FA2C8E0567F5183 FOREIGN KEY (film_id) REFERENCES film (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_5FA2C8E04BBC2705 FOREIGN KEY (version_id) REFERENCES version (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_5FA2C8E0567F5183 ON film_version (film_id)');
        $this->addSql('CREATE INDEX IDX_5FA2C8E04BBC2705 ON film_version (version_id)');
        $this->addSql('CREATE TABLE film_langue (film_id INTEGER NOT NULL, langue_id INTEGER NOT NULL, PRIMARY KEY(film_id, langue_id), CONSTRAINT FK_F8A54884567F5183 FOREIGN KEY (film_id) REFERENCES film (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_F8A548842AADBACD FOREIGN KEY (langue_id) REFERENCES langue (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_F8A54884567F5183 ON film_langue (film_id)');
        $this->addSql('CREATE INDEX IDX_F8A548842AADBACD ON film_langue (langue_id)');
        $this->addSql('CREATE TABLE langue (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(50) NOT NULL)');
        $this->addSql('CREATE TABLE serie (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, titre_original VARCHAR(255) DEFAULT NULL, annee_sortie INTEGER DEFAULT NULL, media VARCHAR(255) DEFAULT NULL, vu BOOLEAN NOT NULL, a_garder BOOLEAN NOT NULL, coup_de_coeur BOOLEAN NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('CREATE TABLE version (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(50) NOT NULL)');
        $this->addSql('DROP TABLE cat_video');
        $this->addSql('DROP TABLE video');
        $this->addSql('DROP TABLE video_cat_video');
        $this->addSql('ALTER TABLE film ADD COLUMN code_tmbd INTEGER DEFAULT NULL');
        $this->addSql('ALTER TABLE film ADD COLUMN description CLOB DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cat_video (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, parent_id INTEGER DEFAULT NULL, name VARCHAR(50) NOT NULL COLLATE "BINARY", CONSTRAINT FK_C0BC6AC1727ACA70 FOREIGN KEY (parent_id) REFERENCES cat_video (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_C0BC6AC1727ACA70 ON cat_video (parent_id)');
        $this->addSql('CREATE TABLE video (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, titre VARCHAR(255) NOT NULL COLLATE "BINARY", titre_original VARCHAR(255) DEFAULT NULL COLLATE "BINARY", annee_sortie INTEGER DEFAULT NULL, media VARCHAR(255) DEFAULT NULL COLLATE "BINARY", vu BOOLEAN NOT NULL, a_garder BOOLEAN NOT NULL, coup_de_coeur BOOLEAN NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('CREATE TABLE video_cat_video (video_id INTEGER NOT NULL, cat_video_id INTEGER NOT NULL, PRIMARY KEY(video_id, cat_video_id), CONSTRAINT FK_2C3175429C1004E FOREIGN KEY (video_id) REFERENCES video (id) ON UPDATE NO ACTION ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_2C31754ABE9450D FOREIGN KEY (cat_video_id) REFERENCES cat_video (id) ON UPDATE NO ACTION ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_2C31754ABE9450D ON video_cat_video (cat_video_id)');
        $this->addSql('CREATE INDEX IDX_2C3175429C1004E ON video_cat_video (video_id)');
        $this->addSql('DROP TABLE film_genre');
        $this->addSql('DROP TABLE film_version');
        $this->addSql('DROP TABLE film_langue');
        $this->addSql('DROP TABLE langue');
        $this->addSql('DROP TABLE serie');
        $this->addSql('DROP TABLE version');
        $this->addSql('CREATE TEMPORARY TABLE __temp__film AS SELECT id, titre, titre_original, annee_sortie, media, vu, a_garder, coup_de_coeur, created_at, updated_at FROM film');
        $this->addSql('DROP TABLE film');
        $this->addSql('CREATE TABLE film (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, titre_original VARCHAR(255) DEFAULT NULL, annee_sortie INTEGER DEFAULT NULL, media VARCHAR(255) DEFAULT NULL, vu BOOLEAN NOT NULL, a_garder BOOLEAN NOT NULL, coup_de_coeur BOOLEAN NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('INSERT INTO film (id, titre, titre_original, annee_sortie, media, vu, a_garder, coup_de_coeur, created_at, updated_at) SELECT id, titre, titre_original, annee_sortie, media, vu, a_garder, coup_de_coeur, created_at, updated_at FROM __temp__film');
        $this->addSql('DROP TABLE __temp__film');
    }
}
