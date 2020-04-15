<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200415174204 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE whoshasvisited');
        $this->addSql('DROP TABLE whosonline');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE whoshasvisited (id INT AUTO_INCREMENT NOT NULL, visiteur_id INT NOT NULL, visited_time INT NOT NULL, visited_ip VARCHAR(150) DEFAULT NULL COLLATE utf8mb4_unicode_ci, INDEX IDX_C5AA8AD07F72333D (visiteur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE whosonline (id INT AUTO_INCREMENT NOT NULL, online_id INT DEFAULT NULL, online_time INT NOT NULL, online_ip VARCHAR(150) DEFAULT NULL COLLATE utf8mb4_unicode_ci, INDEX IDX_EB1FB2C370A5426E (online_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE whoshasvisited ADD CONSTRAINT FK_C5AA8AD07F72333D FOREIGN KEY (visiteur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE whosonline ADD CONSTRAINT FK_EB1FB2C370A5426E FOREIGN KEY (online_id) REFERENCES user (id)');
    }
}
