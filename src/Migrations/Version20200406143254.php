<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200406143254 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE messagerie (id INT AUTO_INCREMENT NOT NULL, expediteur_id INT NOT NULL, message LONGTEXT NOT NULL, titre VARCHAR(255) NOT NULL, date_creation DATETIME NOT NULL, lu TINYINT(1) NOT NULL, INDEX IDX_14E8F60C10335F61 (expediteur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messagerie_user (messagerie_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_3F145465836C1031 (messagerie_id), INDEX IDX_3F145465A76ED395 (user_id), PRIMARY KEY(messagerie_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE messagerie ADD CONSTRAINT FK_14E8F60C10335F61 FOREIGN KEY (expediteur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE messagerie_user ADD CONSTRAINT FK_3F145465836C1031 FOREIGN KEY (messagerie_id) REFERENCES messagerie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE messagerie_user ADD CONSTRAINT FK_3F145465A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE messagerie_user DROP FOREIGN KEY FK_3F145465836C1031');
        $this->addSql('DROP TABLE messagerie');
        $this->addSql('DROP TABLE messagerie_user');
    }
}
