<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200509154924 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE forum_discussion_tag (forum_discussion_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_E9119704F25ED9D4 (forum_discussion_id), INDEX IDX_E9119704BAD26311 (tag_id), PRIMARY KEY(forum_discussion_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE forum_discussion_tag ADD CONSTRAINT FK_E9119704F25ED9D4 FOREIGN KEY (forum_discussion_id) REFERENCES forum_discussion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE forum_discussion_tag ADD CONSTRAINT FK_E9119704BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE forum_discussion_tag DROP FOREIGN KEY FK_E9119704BAD26311');
        $this->addSql('DROP TABLE forum_discussion_tag');
        $this->addSql('DROP TABLE tag');
    }
}
