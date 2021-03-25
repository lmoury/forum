<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210323211035 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE signalement_raison (id INT AUTO_INCREMENT NOT NULL, signaleur_id INT NOT NULL, signalement_id INT NOT NULL, raison VARCHAR(255) NOT NULL, date_signalement DATETIME NOT NULL, INDEX IDX_77DF84BBC5687B3E (signaleur_id), INDEX IDX_77DF84BB65C5E57E (signalement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE signalement_raison ADD CONSTRAINT FK_77DF84BBC5687B3E FOREIGN KEY (signaleur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE signalement_raison ADD CONSTRAINT FK_77DF84BB65C5E57E FOREIGN KEY (signalement_id) REFERENCES signalement (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE signalement_raison');
    }
}
