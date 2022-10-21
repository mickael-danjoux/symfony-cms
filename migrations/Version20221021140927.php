<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221021140927 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, root_id INT DEFAULT NULL, parent_id INT DEFAULT NULL, page_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, lft INT NOT NULL, lvl INT NOT NULL, rgt INT NOT NULL, slug VARCHAR(255) NOT NULL, sort INT DEFAULT 1 NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', dtype VARCHAR(255) NOT NULL, type VARCHAR(255) DEFAULT NULL, url LONGTEXT DEFAULT NULL, new_tab TINYINT(1) DEFAULT 0, INDEX IDX_64C19C179066886 (root_id), INDEX IDX_64C19C1727ACA70 (parent_id), INDEX IDX_64C19C1C4663E4 (page_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE page (id INT AUTO_INCREMENT NOT NULL, seo_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT DEFAULT NULL, path VARCHAR(255) NOT NULL, published TINYINT(1) NOT NULL, start_publishing_at DATE NOT NULL, end_publishing_at DATE DEFAULT NULL, controller VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_140AB62097E3DD86 (seo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE seo (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C179066886 FOREIGN KEY (root_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1727ACA70 FOREIGN KEY (parent_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1C4663E4 FOREIGN KEY (page_id) REFERENCES page (id)');
        $this->addSql('ALTER TABLE page ADD CONSTRAINT FK_140AB62097E3DD86 FOREIGN KEY (seo_id) REFERENCES seo (id)');
        $this->addSql('DROP TABLE referencing');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE referencing (id INT AUTO_INCREMENT NOT NULL, page_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, url VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, title VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, description VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_E844F4CF47645AE (url), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C179066886');
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C1727ACA70');
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C1C4663E4');
        $this->addSql('ALTER TABLE page DROP FOREIGN KEY FK_140AB62097E3DD86');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE page');
        $this->addSql('DROP TABLE seo');
    }
}
