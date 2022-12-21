<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221221085245 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE media (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, url VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', discr VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE media_image (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE media_image_page (id INT NOT NULL, page_id INT NOT NULL, INDEX IDX_EC15922AC4663E4 (page_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE media_image ADD CONSTRAINT FK_DA24C0EEBF396750 FOREIGN KEY (id) REFERENCES media (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE media_image_page ADD CONSTRAINT FK_EC15922AC4663E4 FOREIGN KEY (page_id) REFERENCES page (id)');
        $this->addSql('ALTER TABLE media_image_page ADD CONSTRAINT FK_EC15922ABF396750 FOREIGN KEY (id) REFERENCES media (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE page ADD editor_content JSON DEFAULT NULL, ADD html_css JSON DEFAULT NULL, DROP content');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media_image DROP FOREIGN KEY FK_DA24C0EEBF396750');
        $this->addSql('ALTER TABLE media_image_page DROP FOREIGN KEY FK_EC15922AC4663E4');
        $this->addSql('ALTER TABLE media_image_page DROP FOREIGN KEY FK_EC15922ABF396750');
        $this->addSql('DROP TABLE media');
        $this->addSql('DROP TABLE media_image');
        $this->addSql('DROP TABLE media_image_page');
        $this->addSql('ALTER TABLE page ADD content LONGTEXT DEFAULT NULL, DROP editor_content, DROP html_css');
    }
}
