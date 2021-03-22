<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210322145439 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE forum_discussion ADD prefixe_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE forum_discussion ADD CONSTRAINT FK_428F444AC85A3F32 FOREIGN KEY (prefixe_id) REFERENCES prefixe (id)');
        $this->addSql('CREATE INDEX IDX_428F444AC85A3F32 ON forum_discussion (prefixe_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE forum_discussion DROP FOREIGN KEY FK_428F444AC85A3F32');
        $this->addSql('DROP INDEX IDX_428F444AC85A3F32 ON forum_discussion');
        $this->addSql('ALTER TABLE forum_discussion DROP prefixe_id');
    }
}
