<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210315145802 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE signalement (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, discussion_id INT DEFAULT NULL, commentaire_id INT DEFAULT NULL, chatbox_id INT DEFAULT NULL, raison VARCHAR(255) NOT NULL, date_signal DATETIME NOT NULL, lu TINYINT(1) NOT NULL, INDEX IDX_F4B55114A76ED395 (user_id), INDEX IDX_F4B551141ADED311 (discussion_id), INDEX IDX_F4B55114BA9CD190 (commentaire_id), INDEX IDX_F4B5511453527A38 (chatbox_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE signalement ADD CONSTRAINT FK_F4B55114A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE signalement ADD CONSTRAINT FK_F4B551141ADED311 FOREIGN KEY (discussion_id) REFERENCES forum_discussion (id)');
        $this->addSql('ALTER TABLE signalement ADD CONSTRAINT FK_F4B55114BA9CD190 FOREIGN KEY (commentaire_id) REFERENCES forum_commentaire (id)');
        $this->addSql('ALTER TABLE signalement ADD CONSTRAINT FK_F4B5511453527A38 FOREIGN KEY (chatbox_id) REFERENCES chatbox (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE signalement');
    }
}
