<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210322145220 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE prefixe (id INT AUTO_INCREMENT NOT NULL, prefix VARCHAR(50) NOT NULL, couleur INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prefixe_forum_categorie (prefixe_id INT NOT NULL, forum_categorie_id INT NOT NULL, INDEX IDX_FE4F7F5DC85A3F32 (prefixe_id), INDEX IDX_FE4F7F5D842B116A (forum_categorie_id), PRIMARY KEY(prefixe_id, forum_categorie_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE prefixe_forum_categorie ADD CONSTRAINT FK_FE4F7F5DC85A3F32 FOREIGN KEY (prefixe_id) REFERENCES prefixe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE prefixe_forum_categorie ADD CONSTRAINT FK_FE4F7F5D842B116A FOREIGN KEY (forum_categorie_id) REFERENCES forum_categorie (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE prefixe_forum_categorie DROP FOREIGN KEY FK_FE4F7F5DC85A3F32');
        $this->addSql('DROP TABLE prefixe');
        $this->addSql('DROP TABLE prefixe_forum_categorie');
    }
}
