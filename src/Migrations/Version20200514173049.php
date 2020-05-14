<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200514173049 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_bannir ADD banni_id INT NOT NULL');
        $this->addSql('ALTER TABLE user_bannir ADD CONSTRAINT FK_A67AECD611FF3C53 FOREIGN KEY (banni_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A67AECD611FF3C53 ON user_bannir (banni_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_bannir DROP FOREIGN KEY FK_A67AECD611FF3C53');
        $this->addSql('DROP INDEX UNIQ_A67AECD611FF3C53 ON user_bannir');
        $this->addSql('ALTER TABLE user_bannir DROP banni_id');
    }
}
