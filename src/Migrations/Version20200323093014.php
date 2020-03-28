<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200323093014 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE whosonline (id INT AUTO_INCREMENT NOT NULL, online_id INT DEFAULT NULL, online_time INT NOT NULL, online_ip VARCHAR(150) DEFAULT NULL, INDEX IDX_EB1FB2C370A5426E (online_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE whosonline ADD CONSTRAINT FK_EB1FB2C370A5426E FOREIGN KEY (online_id) REFERENCES user (id)');
        $this->addSql('DROP INDEX visiteur_id_2 ON whoshasvisited');
        $this->addSql('DROP INDEX visiteur_id_3 ON whoshasvisited');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE whosonline');
        $this->addSql('CREATE INDEX visiteur_id_2 ON whoshasvisited (visiteur_id)');
        $this->addSql('CREATE INDEX visiteur_id_3 ON whoshasvisited (visiteur_id)');
    }
}
