<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210412155352 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE chatbox (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, message VARCHAR(255) NOT NULL, poster DATETIME NOT NULL, INDEX IDX_7472FC2FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE config (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, valeur VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, sujet VARCHAR(255) NOT NULL, message LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE conversation (id INT AUTO_INCREMENT NOT NULL, expediteur_id INT NOT NULL, message LONGTEXT NOT NULL, titre VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, locked TINYINT(1) DEFAULT \'0\' NOT NULL, INDEX IDX_8A8E26E910335F61 (expediteur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE conversation_reponse (id INT AUTO_INCREMENT NOT NULL, conversation_rep_id INT NOT NULL, auteur_id INT NOT NULL, message LONGTEXT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_C4E0D0CD6609F32A (conversation_rep_id), INDEX IDX_C4E0D0CD60BB6FE6 (auteur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE conversation_user (id INT AUTO_INCREMENT NOT NULL, conversation_id INT NOT NULL, participant_id INT NOT NULL, lu TINYINT(1) DEFAULT \'0\' NOT NULL, important TINYINT(1) DEFAULT \'0\' NOT NULL, INDEX IDX_5AECB5559AC0396 (conversation_id), INDEX IDX_5AECB5559D1C3019 (participant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forum_categorie (id INT AUTO_INCREMENT NOT NULL, access_id INT DEFAULT NULL, categorie VARCHAR(50) NOT NULL, parent INT DEFAULT NULL, icon VARCHAR(35) DEFAULT NULL, ordre INT NOT NULL, locked TINYINT(1) NOT NULL, INDEX IDX_773452624FEA67CF (access_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forum_commentaire (id INT AUTO_INCREMENT NOT NULL, discussion_id INT NOT NULL, auteur_id INT NOT NULL, commentaire LONGTEXT NOT NULL, date_creation DATETIME NOT NULL, date_edition DATETIME NOT NULL, INDEX IDX_61C4EB1E1ADED311 (discussion_id), INDEX IDX_61C4EB1E60BB6FE6 (auteur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forum_discussion (id INT AUTO_INCREMENT NOT NULL, categorie_id INT NOT NULL, auteur_id INT NOT NULL, prefixe_id INT DEFAULT NULL, titre VARCHAR(120) NOT NULL, message LONGTEXT NOT NULL, date_creation DATETIME NOT NULL, date_edition DATETIME NOT NULL, date_new_com DATETIME DEFAULT NULL, affichage INT NOT NULL, locked TINYINT(1) NOT NULL, important TINYINT(1) NOT NULL, INDEX IDX_428F444ABCF5E72D (categorie_id), INDEX IDX_428F444A60BB6FE6 (auteur_id), INDEX IDX_428F444AC85A3F32 (prefixe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forum_discussion_tag (forum_discussion_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_E9119704F25ED9D4 (forum_discussion_id), INDEX IDX_E9119704BAD26311 (tag_id), PRIMARY KEY(forum_discussion_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forum_discussion_view (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, discussion_id INT NOT NULL, INDEX IDX_8DCB252DA76ED395 (user_id), INDEX IDX_8DCB252D1ADED311 (discussion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE likes (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, discussion_id INT DEFAULT NULL, commentaire_id INT DEFAULT NULL, auteur_id INT NOT NULL, INDEX IDX_49CA4E7DA76ED395 (user_id), INDEX IDX_49CA4E7D1ADED311 (discussion_id), INDEX IDX_49CA4E7DBA9CD190 (commentaire_id), INDEX IDX_49CA4E7D60BB6FE6 (auteur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notice (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, message LONGTEXT NOT NULL, actif TINYINT(1) NOT NULL, emplacement INT NOT NULL, dismissed TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prefixe (id INT AUTO_INCREMENT NOT NULL, prefix VARCHAR(50) NOT NULL, couleur INT NOT NULL, icon VARCHAR(35) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prefixe_forum_categorie (prefixe_id INT NOT NULL, forum_categorie_id INT NOT NULL, INDEX IDX_FE4F7F5DC85A3F32 (prefixe_id), INDEX IDX_FE4F7F5D842B116A (forum_categorie_id), PRIMARY KEY(prefixe_id, forum_categorie_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE signalement (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, titre VARCHAR(255) NOT NULL, message LONGTEXT NOT NULL, date_message DATETIME NOT NULL, lu TINYINT(1) DEFAULT \'0\' NOT NULL, type INT NOT NULL, id_signal INT NOT NULL, created_at DATETIME NOT NULL, statut INT DEFAULT 1 NOT NULL, date_new_raison DATETIME NOT NULL, INDEX IDX_F4B55114A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE signalement_raison (id INT AUTO_INCREMENT NOT NULL, signaleur_id INT NOT NULL, signalement_id INT NOT NULL, raison VARCHAR(255) NOT NULL, date_signalement DATETIME NOT NULL, INDEX IDX_77DF84BBC5687B3E (signaleur_id), INDEX IDX_77DF84BB65C5E57E (signalement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE social (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, url VARCHAR(255) DEFAULT NULL, actif TINYINT(1) NOT NULL, class VARCHAR(20) DEFAULT NULL, icon VARCHAR(25) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, role_id INT NOT NULL, username VARCHAR(50) NOT NULL, email VARCHAR(255) NOT NULL, sexe INT NOT NULL, password VARCHAR(255) NOT NULL, avatar VARCHAR(255) NOT NULL, date_naissance DATE NOT NULL, date_inscription DATETIME NOT NULL, date_visite DATETIME NOT NULL, lost_password_key VARCHAR(255) DEFAULT NULL, INDEX IDX_8D93D649D60322AC (role_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_bannir (id INT AUTO_INCREMENT NOT NULL, banni_id INT NOT NULL, motif VARCHAR(255) NOT NULL, fin DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_A67AECD611FF3C53 (banni_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_role (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(20) NOT NULL, role VARCHAR(25) NOT NULL, level INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE chatbox ADD CONSTRAINT FK_7472FC2FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E910335F61 FOREIGN KEY (expediteur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE conversation_reponse ADD CONSTRAINT FK_C4E0D0CD6609F32A FOREIGN KEY (conversation_rep_id) REFERENCES conversation (id)');
        $this->addSql('ALTER TABLE conversation_reponse ADD CONSTRAINT FK_C4E0D0CD60BB6FE6 FOREIGN KEY (auteur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE conversation_user ADD CONSTRAINT FK_5AECB5559AC0396 FOREIGN KEY (conversation_id) REFERENCES conversation (id)');
        $this->addSql('ALTER TABLE conversation_user ADD CONSTRAINT FK_5AECB5559D1C3019 FOREIGN KEY (participant_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE forum_categorie ADD CONSTRAINT FK_773452624FEA67CF FOREIGN KEY (access_id) REFERENCES user_role (id)');
        $this->addSql('ALTER TABLE forum_commentaire ADD CONSTRAINT FK_61C4EB1E1ADED311 FOREIGN KEY (discussion_id) REFERENCES forum_discussion (id)');
        $this->addSql('ALTER TABLE forum_commentaire ADD CONSTRAINT FK_61C4EB1E60BB6FE6 FOREIGN KEY (auteur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE forum_discussion ADD CONSTRAINT FK_428F444ABCF5E72D FOREIGN KEY (categorie_id) REFERENCES forum_categorie (id)');
        $this->addSql('ALTER TABLE forum_discussion ADD CONSTRAINT FK_428F444A60BB6FE6 FOREIGN KEY (auteur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE forum_discussion ADD CONSTRAINT FK_428F444AC85A3F32 FOREIGN KEY (prefixe_id) REFERENCES prefixe (id)');
        $this->addSql('ALTER TABLE forum_discussion_tag ADD CONSTRAINT FK_E9119704F25ED9D4 FOREIGN KEY (forum_discussion_id) REFERENCES forum_discussion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE forum_discussion_tag ADD CONSTRAINT FK_E9119704BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE forum_discussion_view ADD CONSTRAINT FK_8DCB252DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE forum_discussion_view ADD CONSTRAINT FK_8DCB252D1ADED311 FOREIGN KEY (discussion_id) REFERENCES forum_discussion (id)');
        $this->addSql('ALTER TABLE likes ADD CONSTRAINT FK_49CA4E7DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE likes ADD CONSTRAINT FK_49CA4E7D1ADED311 FOREIGN KEY (discussion_id) REFERENCES forum_discussion (id)');
        $this->addSql('ALTER TABLE likes ADD CONSTRAINT FK_49CA4E7DBA9CD190 FOREIGN KEY (commentaire_id) REFERENCES forum_commentaire (id)');
        $this->addSql('ALTER TABLE likes ADD CONSTRAINT FK_49CA4E7D60BB6FE6 FOREIGN KEY (auteur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE prefixe_forum_categorie ADD CONSTRAINT FK_FE4F7F5DC85A3F32 FOREIGN KEY (prefixe_id) REFERENCES prefixe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE prefixe_forum_categorie ADD CONSTRAINT FK_FE4F7F5D842B116A FOREIGN KEY (forum_categorie_id) REFERENCES forum_categorie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE signalement ADD CONSTRAINT FK_F4B55114A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE signalement_raison ADD CONSTRAINT FK_77DF84BBC5687B3E FOREIGN KEY (signaleur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE signalement_raison ADD CONSTRAINT FK_77DF84BB65C5E57E FOREIGN KEY (signalement_id) REFERENCES signalement (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649D60322AC FOREIGN KEY (role_id) REFERENCES user_role (id)');
        $this->addSql('ALTER TABLE user_bannir ADD CONSTRAINT FK_A67AECD611FF3C53 FOREIGN KEY (banni_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE conversation_reponse DROP FOREIGN KEY FK_C4E0D0CD6609F32A');
        $this->addSql('ALTER TABLE conversation_user DROP FOREIGN KEY FK_5AECB5559AC0396');
        $this->addSql('ALTER TABLE forum_discussion DROP FOREIGN KEY FK_428F444ABCF5E72D');
        $this->addSql('ALTER TABLE prefixe_forum_categorie DROP FOREIGN KEY FK_FE4F7F5D842B116A');
        $this->addSql('ALTER TABLE likes DROP FOREIGN KEY FK_49CA4E7DBA9CD190');
        $this->addSql('ALTER TABLE forum_commentaire DROP FOREIGN KEY FK_61C4EB1E1ADED311');
        $this->addSql('ALTER TABLE forum_discussion_tag DROP FOREIGN KEY FK_E9119704F25ED9D4');
        $this->addSql('ALTER TABLE forum_discussion_view DROP FOREIGN KEY FK_8DCB252D1ADED311');
        $this->addSql('ALTER TABLE likes DROP FOREIGN KEY FK_49CA4E7D1ADED311');
        $this->addSql('ALTER TABLE forum_discussion DROP FOREIGN KEY FK_428F444AC85A3F32');
        $this->addSql('ALTER TABLE prefixe_forum_categorie DROP FOREIGN KEY FK_FE4F7F5DC85A3F32');
        $this->addSql('ALTER TABLE signalement_raison DROP FOREIGN KEY FK_77DF84BB65C5E57E');
        $this->addSql('ALTER TABLE forum_discussion_tag DROP FOREIGN KEY FK_E9119704BAD26311');
        $this->addSql('ALTER TABLE chatbox DROP FOREIGN KEY FK_7472FC2FA76ED395');
        $this->addSql('ALTER TABLE conversation DROP FOREIGN KEY FK_8A8E26E910335F61');
        $this->addSql('ALTER TABLE conversation_reponse DROP FOREIGN KEY FK_C4E0D0CD60BB6FE6');
        $this->addSql('ALTER TABLE conversation_user DROP FOREIGN KEY FK_5AECB5559D1C3019');
        $this->addSql('ALTER TABLE forum_commentaire DROP FOREIGN KEY FK_61C4EB1E60BB6FE6');
        $this->addSql('ALTER TABLE forum_discussion DROP FOREIGN KEY FK_428F444A60BB6FE6');
        $this->addSql('ALTER TABLE forum_discussion_view DROP FOREIGN KEY FK_8DCB252DA76ED395');
        $this->addSql('ALTER TABLE likes DROP FOREIGN KEY FK_49CA4E7DA76ED395');
        $this->addSql('ALTER TABLE likes DROP FOREIGN KEY FK_49CA4E7D60BB6FE6');
        $this->addSql('ALTER TABLE signalement DROP FOREIGN KEY FK_F4B55114A76ED395');
        $this->addSql('ALTER TABLE signalement_raison DROP FOREIGN KEY FK_77DF84BBC5687B3E');
        $this->addSql('ALTER TABLE user_bannir DROP FOREIGN KEY FK_A67AECD611FF3C53');
        $this->addSql('ALTER TABLE forum_categorie DROP FOREIGN KEY FK_773452624FEA67CF');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649D60322AC');
        $this->addSql('DROP TABLE chatbox');
        $this->addSql('DROP TABLE config');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE conversation');
        $this->addSql('DROP TABLE conversation_reponse');
        $this->addSql('DROP TABLE conversation_user');
        $this->addSql('DROP TABLE forum_categorie');
        $this->addSql('DROP TABLE forum_commentaire');
        $this->addSql('DROP TABLE forum_discussion');
        $this->addSql('DROP TABLE forum_discussion_tag');
        $this->addSql('DROP TABLE forum_discussion_view');
        $this->addSql('DROP TABLE likes');
        $this->addSql('DROP TABLE notice');
        $this->addSql('DROP TABLE prefixe');
        $this->addSql('DROP TABLE prefixe_forum_categorie');
        $this->addSql('DROP TABLE signalement');
        $this->addSql('DROP TABLE signalement_raison');
        $this->addSql('DROP TABLE social');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_bannir');
        $this->addSql('DROP TABLE user_role');
    }
}
