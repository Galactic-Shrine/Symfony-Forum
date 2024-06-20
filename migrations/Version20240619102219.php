<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240619102219 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE file (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', post_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', filename VARCHAR(245) NOT NULL, original_name VARCHAR(245) NOT NULL, path VARCHAR(255) NOT NULL, extension VARCHAR(10) NOT NULL, size BIGINT NOT NULL, upload_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_8C9F3610B548B0F (path), INDEX IDX_8C9F36104B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forum_category (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, slug VARCHAR(255) NOT NULL, position INT UNSIGNED DEFAULT 0 NOT NULL, authorized_roles LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forum_forum (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', category_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, slug VARCHAR(255) NOT NULL, position INT UNSIGNED DEFAULT 0 NOT NULL, authorized_roles LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', INDEX IDX_9D5F181A12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forum_post (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', thread_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', user_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', content LONGTEXT NOT NULL, ip VARCHAR(255) NOT NULL, moderate_reason LONGTEXT DEFAULT NULL, vote_up INT DEFAULT NULL, INDEX IDX_996BCC5AE2904019 (thread_id), INDEX IDX_996BCC5AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forum_post_report (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', post_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', user_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', processed TINYINT(1) NOT NULL, create_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_F23910434B89032C (post_id), INDEX IDX_F2391043A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forum_post_vote (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', post_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', thread_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', user_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', vote_type INT NOT NULL, INDEX IDX_AD0C66394B89032C (post_id), INDEX IDX_AD0C6639E2904019 (thread_id), INDEX IDX_AD0C6639A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forum_rules (id INT AUTO_INCREMENT NOT NULL, lang VARCHAR(50) NOT NULL, content LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forum_sub_forum (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', forum_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, slug VARCHAR(255) NOT NULL, position INT UNSIGNED DEFAULT 0 NOT NULL, authorized_roles LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', INDEX IDX_57265E2229CCBAD0 (forum_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forum_subscription (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', thread_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', user_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_9B189294E2904019 (thread_id), INDEX IDX_9B189294A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forum_thread (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', forum_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', sub_forum_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', author_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', title VARCHAR(255) NOT NULL, sub_title VARCHAR(510) DEFAULT NULL, slug VARCHAR(255) NOT NULL, is_pin TINYINT(1) DEFAULT NULL, is_resolved TINYINT(1) DEFAULT NULL, is_locked TINYINT(1) DEFAULT NULL, create_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', number_replies INT NOT NULL, replies_create_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_298F7F5229CCBAD0 (forum_id), INDEX IDX_298F7F5293635489 (sub_forum_id), UNIQUE INDEX UNIQ_298F7F52F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE file ADD CONSTRAINT FK_8C9F36104B89032C FOREIGN KEY (post_id) REFERENCES forum_post (id)');
        $this->addSql('ALTER TABLE forum_forum ADD CONSTRAINT FK_9D5F181A12469DE2 FOREIGN KEY (category_id) REFERENCES forum_category (id)');
        $this->addSql('ALTER TABLE forum_post ADD CONSTRAINT FK_996BCC5AE2904019 FOREIGN KEY (thread_id) REFERENCES forum_thread (id)');
        $this->addSql('ALTER TABLE forum_post ADD CONSTRAINT FK_996BCC5AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE forum_post_report ADD CONSTRAINT FK_F23910434B89032C FOREIGN KEY (post_id) REFERENCES forum_post (id)');
        $this->addSql('ALTER TABLE forum_post_report ADD CONSTRAINT FK_F2391043A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE forum_post_vote ADD CONSTRAINT FK_AD0C66394B89032C FOREIGN KEY (post_id) REFERENCES forum_post (id)');
        $this->addSql('ALTER TABLE forum_post_vote ADD CONSTRAINT FK_AD0C6639E2904019 FOREIGN KEY (thread_id) REFERENCES forum_thread (id)');
        $this->addSql('ALTER TABLE forum_post_vote ADD CONSTRAINT FK_AD0C6639A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE forum_sub_forum ADD CONSTRAINT FK_57265E2229CCBAD0 FOREIGN KEY (forum_id) REFERENCES forum_forum (id)');
        $this->addSql('ALTER TABLE forum_subscription ADD CONSTRAINT FK_9B189294E2904019 FOREIGN KEY (thread_id) REFERENCES forum_thread (id)');
        $this->addSql('ALTER TABLE forum_subscription ADD CONSTRAINT FK_9B189294A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE forum_thread ADD CONSTRAINT FK_298F7F5229CCBAD0 FOREIGN KEY (forum_id) REFERENCES forum_forum (id)');
        $this->addSql('ALTER TABLE forum_thread ADD CONSTRAINT FK_298F7F5293635489 FOREIGN KEY (sub_forum_id) REFERENCES forum_sub_forum (id)');
        $this->addSql('ALTER TABLE forum_thread ADD CONSTRAINT FK_298F7F52F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE file DROP FOREIGN KEY FK_8C9F36104B89032C');
        $this->addSql('ALTER TABLE forum_forum DROP FOREIGN KEY FK_9D5F181A12469DE2');
        $this->addSql('ALTER TABLE forum_post DROP FOREIGN KEY FK_996BCC5AE2904019');
        $this->addSql('ALTER TABLE forum_post DROP FOREIGN KEY FK_996BCC5AA76ED395');
        $this->addSql('ALTER TABLE forum_post_report DROP FOREIGN KEY FK_F23910434B89032C');
        $this->addSql('ALTER TABLE forum_post_report DROP FOREIGN KEY FK_F2391043A76ED395');
        $this->addSql('ALTER TABLE forum_post_vote DROP FOREIGN KEY FK_AD0C66394B89032C');
        $this->addSql('ALTER TABLE forum_post_vote DROP FOREIGN KEY FK_AD0C6639E2904019');
        $this->addSql('ALTER TABLE forum_post_vote DROP FOREIGN KEY FK_AD0C6639A76ED395');
        $this->addSql('ALTER TABLE forum_sub_forum DROP FOREIGN KEY FK_57265E2229CCBAD0');
        $this->addSql('ALTER TABLE forum_subscription DROP FOREIGN KEY FK_9B189294E2904019');
        $this->addSql('ALTER TABLE forum_subscription DROP FOREIGN KEY FK_9B189294A76ED395');
        $this->addSql('ALTER TABLE forum_thread DROP FOREIGN KEY FK_298F7F5229CCBAD0');
        $this->addSql('ALTER TABLE forum_thread DROP FOREIGN KEY FK_298F7F5293635489');
        $this->addSql('ALTER TABLE forum_thread DROP FOREIGN KEY FK_298F7F52F675F31B');
        $this->addSql('DROP TABLE file');
        $this->addSql('DROP TABLE forum_category');
        $this->addSql('DROP TABLE forum_forum');
        $this->addSql('DROP TABLE forum_post');
        $this->addSql('DROP TABLE forum_post_report');
        $this->addSql('DROP TABLE forum_post_vote');
        $this->addSql('DROP TABLE forum_rules');
        $this->addSql('DROP TABLE forum_sub_forum');
        $this->addSql('DROP TABLE forum_subscription');
        $this->addSql('DROP TABLE forum_thread');
    }
}
