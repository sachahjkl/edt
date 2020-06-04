<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200603194900 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE absence_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE actualite_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE utilisateur_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE cours_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE document_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE formation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE groupe_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE matiere_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE note_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE salle_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE absence (id INT NOT NULL, absent_id INT NOT NULL, cours_id INT NOT NULL, motif VARCHAR(255) NOT NULL, statut BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_765AE0C9E1399015 ON absence (absent_id)');
        $this->addSql('CREATE INDEX IDX_765AE0C97ECF78B0 ON absence (cours_id)');
        $this->addSql('CREATE TABLE actualite (id INT NOT NULL, auteur_id INT NOT NULL, titre VARCHAR(255) NOT NULL, contenu TEXT NOT NULL, date_creation DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5492819760BB6FE6 ON actualite (auteur_id)');
        $this->addSql('CREATE TABLE utilisateur (id INT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, profile_picture_path VARCHAR(255) DEFAULT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1D1C63B3F85E0677 ON utilisateur (username)');
        $this->addSql('CREATE TABLE administrateur (id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE cours (id INT NOT NULL, salle_id INT NOT NULL, matiere_id INT NOT NULL, professeur_id INT NOT NULL, groupe_id INT NOT NULL, debut TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, fin TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, devoirs TEXT DEFAULT NULL, statut BOOLEAN NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_FDCA8C9CDC304035 ON cours (salle_id)');
        $this->addSql('CREATE INDEX IDX_FDCA8C9CF46CD258 ON cours (matiere_id)');
        $this->addSql('CREATE INDEX IDX_FDCA8C9CBAB22EE9 ON cours (professeur_id)');
        $this->addSql('CREATE INDEX IDX_FDCA8C9C7A45358C ON cours (groupe_id)');
        $this->addSql('CREATE TABLE document (id INT NOT NULL, proprietaire_id INT NOT NULL, titre VARCHAR(255) NOT NULL, file_path VARCHAR(255) NOT NULL, partage BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D8698A7676C50E4A ON document (proprietaire_id)');
        $this->addSql('CREATE TABLE personne (id INT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, date_naissance DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE eleve (id INT NOT NULL, groupe_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_ECA105F77A45358C ON eleve (groupe_id)');
        $this->addSql('CREATE TABLE examen (id INT NOT NULL, coefficient DOUBLE PRECISION NOT NULL, note_max DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE formation (id INT NOT NULL, responsable_id INT NOT NULL, intitule VARCHAR(255) NOT NULL, duree_annees SMALLINT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_404021BF53C59D72 ON formation (responsable_id)');
        $this->addSql('CREATE TABLE groupe (id INT NOT NULL, formation_id INT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4B98C215200282E ON groupe (formation_id)');
        $this->addSql('CREATE TABLE matiere (id INT NOT NULL, responsable_id INT NOT NULL, intitule VARCHAR(255) NOT NULL, credits_ects SMALLINT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9014574A53C59D72 ON matiere (responsable_id)');
        $this->addSql('CREATE TABLE matiere_professeur (matiere_id INT NOT NULL, professeur_id INT NOT NULL, PRIMARY KEY(matiere_id, professeur_id))');
        $this->addSql('CREATE INDEX IDX_C56DD937F46CD258 ON matiere_professeur (matiere_id)');
        $this->addSql('CREATE INDEX IDX_C56DD937BAB22EE9 ON matiere_professeur (professeur_id)');
        $this->addSql('CREATE TABLE note (id INT NOT NULL, eleve_id INT NOT NULL, examen_id INT NOT NULL, note DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_CFBDFA14A6CC7B2 ON note (eleve_id)');
        $this->addSql('CREATE INDEX IDX_CFBDFA145C8659A ON note (examen_id)');
        $this->addSql('CREATE TABLE professeur (id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE salle (id INT NOT NULL, numero VARCHAR(15) NOT NULL, capacite SMALLINT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE absence ADD CONSTRAINT FK_765AE0C9E1399015 FOREIGN KEY (absent_id) REFERENCES eleve (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE absence ADD CONSTRAINT FK_765AE0C97ECF78B0 FOREIGN KEY (cours_id) REFERENCES cours (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE actualite ADD CONSTRAINT FK_5492819760BB6FE6 FOREIGN KEY (auteur_id) REFERENCES administrateur (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE administrateur ADD CONSTRAINT FK_32EB52E8BF396750 FOREIGN KEY (id) REFERENCES utilisateur (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cours ADD CONSTRAINT FK_FDCA8C9CDC304035 FOREIGN KEY (salle_id) REFERENCES salle (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cours ADD CONSTRAINT FK_FDCA8C9CF46CD258 FOREIGN KEY (matiere_id) REFERENCES matiere (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cours ADD CONSTRAINT FK_FDCA8C9CBAB22EE9 FOREIGN KEY (professeur_id) REFERENCES professeur (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cours ADD CONSTRAINT FK_FDCA8C9C7A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A7676C50E4A FOREIGN KEY (proprietaire_id) REFERENCES personne (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE personne ADD CONSTRAINT FK_FCEC9EFBF396750 FOREIGN KEY (id) REFERENCES utilisateur (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE eleve ADD CONSTRAINT FK_ECA105F77A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE eleve ADD CONSTRAINT FK_ECA105F7BF396750 FOREIGN KEY (id) REFERENCES utilisateur (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE examen ADD CONSTRAINT FK_514C8FECBF396750 FOREIGN KEY (id) REFERENCES cours (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE formation ADD CONSTRAINT FK_404021BF53C59D72 FOREIGN KEY (responsable_id) REFERENCES professeur (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE groupe ADD CONSTRAINT FK_4B98C215200282E FOREIGN KEY (formation_id) REFERENCES formation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE matiere ADD CONSTRAINT FK_9014574A53C59D72 FOREIGN KEY (responsable_id) REFERENCES professeur (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE matiere_professeur ADD CONSTRAINT FK_C56DD937F46CD258 FOREIGN KEY (matiere_id) REFERENCES matiere (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE matiere_professeur ADD CONSTRAINT FK_C56DD937BAB22EE9 FOREIGN KEY (professeur_id) REFERENCES professeur (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14A6CC7B2 FOREIGN KEY (eleve_id) REFERENCES eleve (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA145C8659A FOREIGN KEY (examen_id) REFERENCES examen (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE professeur ADD CONSTRAINT FK_17A55299BF396750 FOREIGN KEY (id) REFERENCES utilisateur (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE administrateur DROP CONSTRAINT FK_32EB52E8BF396750');
        $this->addSql('ALTER TABLE personne DROP CONSTRAINT FK_FCEC9EFBF396750');
        $this->addSql('ALTER TABLE eleve DROP CONSTRAINT FK_ECA105F7BF396750');
        $this->addSql('ALTER TABLE professeur DROP CONSTRAINT FK_17A55299BF396750');
        $this->addSql('ALTER TABLE actualite DROP CONSTRAINT FK_5492819760BB6FE6');
        $this->addSql('ALTER TABLE absence DROP CONSTRAINT FK_765AE0C97ECF78B0');
        $this->addSql('ALTER TABLE examen DROP CONSTRAINT FK_514C8FECBF396750');
        $this->addSql('ALTER TABLE document DROP CONSTRAINT FK_D8698A7676C50E4A');
        $this->addSql('ALTER TABLE absence DROP CONSTRAINT FK_765AE0C9E1399015');
        $this->addSql('ALTER TABLE note DROP CONSTRAINT FK_CFBDFA14A6CC7B2');
        $this->addSql('ALTER TABLE note DROP CONSTRAINT FK_CFBDFA145C8659A');
        $this->addSql('ALTER TABLE groupe DROP CONSTRAINT FK_4B98C215200282E');
        $this->addSql('ALTER TABLE cours DROP CONSTRAINT FK_FDCA8C9C7A45358C');
        $this->addSql('ALTER TABLE eleve DROP CONSTRAINT FK_ECA105F77A45358C');
        $this->addSql('ALTER TABLE cours DROP CONSTRAINT FK_FDCA8C9CF46CD258');
        $this->addSql('ALTER TABLE matiere_professeur DROP CONSTRAINT FK_C56DD937F46CD258');
        $this->addSql('ALTER TABLE cours DROP CONSTRAINT FK_FDCA8C9CBAB22EE9');
        $this->addSql('ALTER TABLE formation DROP CONSTRAINT FK_404021BF53C59D72');
        $this->addSql('ALTER TABLE matiere DROP CONSTRAINT FK_9014574A53C59D72');
        $this->addSql('ALTER TABLE matiere_professeur DROP CONSTRAINT FK_C56DD937BAB22EE9');
        $this->addSql('ALTER TABLE cours DROP CONSTRAINT FK_FDCA8C9CDC304035');
        $this->addSql('DROP SEQUENCE absence_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE actualite_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE utilisateur_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE cours_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE document_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE formation_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE groupe_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE matiere_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE note_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE salle_id_seq CASCADE');
        $this->addSql('DROP TABLE absence');
        $this->addSql('DROP TABLE actualite');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE administrateur');
        $this->addSql('DROP TABLE cours');
        $this->addSql('DROP TABLE document');
        $this->addSql('DROP TABLE personne');
        $this->addSql('DROP TABLE eleve');
        $this->addSql('DROP TABLE examen');
        $this->addSql('DROP TABLE formation');
        $this->addSql('DROP TABLE groupe');
        $this->addSql('DROP TABLE matiere');
        $this->addSql('DROP TABLE matiere_professeur');
        $this->addSql('DROP TABLE note');
        $this->addSql('DROP TABLE professeur');
        $this->addSql('DROP TABLE salle');
    }
}
