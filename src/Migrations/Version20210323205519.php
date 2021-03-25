<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210323205519 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE signalement DROP FOREIGN KEY FK_F4B551141ADED311');
        $this->addSql('ALTER TABLE signalement DROP FOREIGN KEY FK_F4B5511453527A38');
        $this->addSql('ALTER TABLE signalement DROP FOREIGN KEY FK_F4B55114BA9CD190');
        $this->addSql('DROP INDEX IDX_F4B551141ADED311 ON signalement');
        $this->addSql('DROP INDEX IDX_F4B5511453527A38 ON signalement');
        $this->addSql('DROP INDEX IDX_F4B55114BA9CD190 ON signalement');
        $this->addSql('ALTER TABLE signalement DROP discussion_id, DROP commentaire_id, DROP chatbox_id, DROP raison, DROP date_signal, DROP lu');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE signalement ADD discussion_id INT DEFAULT NULL, ADD commentaire_id INT DEFAULT NULL, ADD chatbox_id INT DEFAULT NULL, ADD raison VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD date_signal DATETIME NOT NULL, ADD lu TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE signalement ADD CONSTRAINT FK_F4B551141ADED311 FOREIGN KEY (discussion_id) REFERENCES forum_discussion (id)');
        $this->addSql('ALTER TABLE signalement ADD CONSTRAINT FK_F4B5511453527A38 FOREIGN KEY (chatbox_id) REFERENCES chatbox (id)');
        $this->addSql('ALTER TABLE signalement ADD CONSTRAINT FK_F4B55114BA9CD190 FOREIGN KEY (commentaire_id) REFERENCES forum_commentaire (id)');
        $this->addSql('CREATE INDEX IDX_F4B551141ADED311 ON signalement (discussion_id)');
        $this->addSql('CREATE INDEX IDX_F4B5511453527A38 ON signalement (chatbox_id)');
        $this->addSql('CREATE INDEX IDX_F4B55114BA9CD190 ON signalement (commentaire_id)');
    }
}
