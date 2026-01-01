<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260101162758 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE billet ADD commande_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE billet ADD CONSTRAINT FK_1F034AF682EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('CREATE INDEX IDX_1F034AF682EA2E54 ON billet (commande_id)');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY `FK_6EEAA67DF77D927C`');
        $this->addSql('DROP INDEX IDX_6EEAA67DF77D927C ON commande');
        $this->addSql('ALTER TABLE commande DROP total, DROP quantite, DROP panier_id, CHANGE statut statut VARCHAR(255) NOT NULL, CHANGE date_creation date_commande DATETIME NOT NULL');
        $this->addSql('ALTER TABLE event ADD price DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE panier ADD quantite INT NOT NULL, ADD event_id INT NOT NULL, DROP status, DROP creat_date, DROP update_date');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF271F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('CREATE INDEX IDX_24CC0DF271F7E88B ON panier (event_id)');
        $this->addSql('ALTER TABLE user CHANGE role roles JSON NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE billet DROP FOREIGN KEY FK_1F034AF682EA2E54');
        $this->addSql('DROP INDEX IDX_1F034AF682EA2E54 ON billet');
        $this->addSql('ALTER TABLE billet DROP commande_id');
        $this->addSql('ALTER TABLE commande ADD total DOUBLE PRECISION NOT NULL, ADD quantite INT NOT NULL, ADD panier_id INT DEFAULT NULL, CHANGE statut statut VARCHAR(50) NOT NULL, CHANGE date_commande date_creation DATETIME NOT NULL');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT `FK_6EEAA67DF77D927C` FOREIGN KEY (panier_id) REFERENCES panier (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_6EEAA67DF77D927C ON commande (panier_id)');
        $this->addSql('ALTER TABLE event DROP price');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF271F7E88B');
        $this->addSql('DROP INDEX IDX_24CC0DF271F7E88B ON panier');
        $this->addSql('ALTER TABLE panier ADD status VARCHAR(50) DEFAULT NULL, ADD creat_date DATETIME NOT NULL, ADD update_date DATETIME DEFAULT NULL, DROP quantite, DROP event_id');
        $this->addSql('ALTER TABLE user CHANGE roles role JSON NOT NULL');
    }
}
