<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221202085552 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE media (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, url VARCHAR(255) NOT NULL, updated_at DATETIME NOT NULL, discr VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE media_image (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE media_image ADD CONSTRAINT FK_DA24C0EEBF396750 FOREIGN KEY (id) REFERENCES media (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE page ADD editor_content JSON DEFAULT NULL, ADD html_css JSON DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media_image DROP FOREIGN KEY FK_DA24C0EEBF396750');
        $this->addSql('DROP TABLE media');
        $this->addSql('DROP TABLE media_image');
        $this->addSql('ALTER TABLE page DROP editor_content, DROP html_css');
    }
}
