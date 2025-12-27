<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251227001645 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE demandestage ADD avis_encadrant LONGTEXT DEFAULT NULL, ADD date_decision DATETIME DEFAULT NULL, ADD valide_fin_stage TINYINT(1) DEFAULT 0 NOT NULL, ADD note INT DEFAULT NULL, ADD appreciation LONGTEXT DEFAULT NULL, ADD etudiant_id INT NOT NULL, ADD stage_id INT NOT NULL');
        $this->addSql('ALTER TABLE demandestage ADD CONSTRAINT FK_F8FC91A7DDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE demandestage ADD CONSTRAINT FK_F8FC91A72298D193 FOREIGN KEY (stage_id) REFERENCES stage (id)');
        $this->addSql('CREATE INDEX IDX_F8FC91A7DDEAB1A3 ON demandestage (etudiant_id)');
        $this->addSql('CREATE INDEX IDX_F8FC91A72298D193 ON demandestage (stage_id)');
        $this->addSql('ALTER TABLE document ADD statut_validation VARCHAR(50) DEFAULT \'En attente\' NOT NULL, ADD demandestage_id INT NOT NULL');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A7666278B41 FOREIGN KEY (demandestage_id) REFERENCES demandestage (id)');
        $this->addSql('CREATE INDEX IDX_D8698A7666278B41 ON document (demandestage_id)');
        $this->addSql('ALTER TABLE stage ADD department_id INT NOT NULL, ADD encadrant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE stage ADD CONSTRAINT FK_C27C9369AE80F5DF FOREIGN KEY (department_id) REFERENCES department (id)');
        $this->addSql('ALTER TABLE stage ADD CONSTRAINT FK_C27C9369FEF1BA4 FOREIGN KEY (encadrant_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_C27C9369AE80F5DF ON stage (department_id)');
        $this->addSql('CREATE INDEX IDX_C27C9369FEF1BA4 ON stage (encadrant_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE demandestage DROP FOREIGN KEY FK_F8FC91A7DDEAB1A3');
        $this->addSql('ALTER TABLE demandestage DROP FOREIGN KEY FK_F8FC91A72298D193');
        $this->addSql('DROP INDEX IDX_F8FC91A7DDEAB1A3 ON demandestage');
        $this->addSql('DROP INDEX IDX_F8FC91A72298D193 ON demandestage');
        $this->addSql('ALTER TABLE demandestage DROP avis_encadrant, DROP date_decision, DROP valide_fin_stage, DROP note, DROP appreciation, DROP etudiant_id, DROP stage_id');
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A7666278B41');
        $this->addSql('DROP INDEX IDX_D8698A7666278B41 ON document');
        $this->addSql('ALTER TABLE document DROP statut_validation, DROP demandestage_id');
        $this->addSql('ALTER TABLE stage DROP FOREIGN KEY FK_C27C9369AE80F5DF');
        $this->addSql('ALTER TABLE stage DROP FOREIGN KEY FK_C27C9369FEF1BA4');
        $this->addSql('DROP INDEX IDX_C27C9369AE80F5DF ON stage');
        $this->addSql('DROP INDEX IDX_C27C9369FEF1BA4 ON stage');
        $this->addSql('ALTER TABLE stage DROP department_id, DROP encadrant_id');
    }
}
