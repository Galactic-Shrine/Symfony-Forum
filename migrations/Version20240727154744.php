<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240727154744 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Modification des types de colonnes id en BINARY(16) avec commentaire (DC2Type:uuid) dans les table config, forum_rules et request_reset_password';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE config CHANGE id id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE forum_rules CHANGE id id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE request_reset_password CHANGE id id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE config CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE forum_rules CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE request_reset_password CHANGE id id INT AUTO_INCREMENT NOT NULL');
    }
}
