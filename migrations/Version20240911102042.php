<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240911102042 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messaging_folder (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messaging_folder_user (messaging_folder_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', user_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_F7115417DF1BDF17 (messaging_folder_id), INDEX IDX_F7115417A76ED395 (user_id), PRIMARY KEY(messaging_folder_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE messaging_folder_user ADD CONSTRAINT FK_F7115417DF1BDF17 FOREIGN KEY (messaging_folder_id) REFERENCES messaging_folder (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE messaging_folder_user ADD CONSTRAINT FK_F7115417A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE messaging_messages ADD folder_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE messaging_messages ADD CONSTRAINT FK_E044CDE5162CB942 FOREIGN KEY (folder_id) REFERENCES messaging_folder (id)');
        $this->addSql('CREATE INDEX IDX_E044CDE5162CB942 ON messaging_messages (folder_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE messaging_messages DROP FOREIGN KEY FK_E044CDE5162CB942');
        $this->addSql('ALTER TABLE messaging_folder_user DROP FOREIGN KEY FK_F7115417DF1BDF17');
        $this->addSql('ALTER TABLE messaging_folder_user DROP FOREIGN KEY FK_F7115417A76ED395');
        $this->addSql('DROP TABLE messaging_folder');
        $this->addSql('DROP TABLE messaging_folder_user');
        $this->addSql('DROP INDEX IDX_E044CDE5162CB942 ON messaging_messages');
        $this->addSql('ALTER TABLE messaging_messages DROP folder_id');
    }
}
