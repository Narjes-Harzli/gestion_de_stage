<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251203144849 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE demandestage (id INT AUTO_INCREMENT NOT NULL, statut VARCHAR(255) NOT NULL, datedemande DATETIME NOT NULL, nom_fichier VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE department (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE document (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, fichier VARCHAR(255) NOT NULL, dateupload DATETIME NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE encadrant (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, telephone VARCHAR(255) DEFAULT NULL, specialite VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE stage (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, typestage VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, duree INT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE etudiant ADD email VARCHAR(255) NOT NULL, ADD telephone VARCHAR(255) NOT NULL, DROP department, DROP description');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE demandestage');
        $this->addSql('DROP TABLE department');
        $this->addSql('DROP TABLE document');
        $this->addSql('DROP TABLE encadrant');
        $this->addSql('DROP TABLE stage');
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE etudiant ADD department VARCHAR(255) NOT NULL, ADD description VARCHAR(255) NOT NULL, DROP email, DROP telephone');
    }
}
