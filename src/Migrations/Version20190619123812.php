<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190619123812 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE affiliates_categories DROP FOREIGN KEY FK_87BE21809F12C49A');
        $this->addSql('ALTER TABLE affiliates_categories DROP FOREIGN KEY FK_87BE218012469DE2');
        $this->addSql('ALTER TABLE jobs DROP FOREIGN KEY FK_A8936DC512469DE2');
        $this->addSql('CREATE TABLE job (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL, company VARCHAR(255) NOT NULL, logo VARCHAR(255) DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, position VARCHAR(255) NOT NULL, location VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, how_to_apply LONGTEXT NOT NULL, token VARCHAR(255) NOT NULL, public TINYINT(1) NOT NULL, activated TINYINT(1) NOT NULL, email VARCHAR(255) NOT NULL, expires_at DATETIME NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_FBD8E0F812469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE affiliate (id INT AUTO_INCREMENT NOT NULL, url VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, token VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE affiliate_category (affiliate_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_CEC6AF8A9F12C49A (affiliate_id), INDEX IDX_CEC6AF8A12469DE2 (category_id), PRIMARY KEY(affiliate_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE job ADD CONSTRAINT FK_FBD8E0F812469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE affiliate_category ADD CONSTRAINT FK_CEC6AF8A9F12C49A FOREIGN KEY (affiliate_id) REFERENCES affiliate (id)');
        $this->addSql('ALTER TABLE affiliate_category ADD CONSTRAINT FK_CEC6AF8A12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('DROP TABLE affiliates');
        $this->addSql('DROP TABLE affiliates_categories');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE jobs');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE job DROP FOREIGN KEY FK_FBD8E0F812469DE2');
        $this->addSql('ALTER TABLE affiliate_category DROP FOREIGN KEY FK_CEC6AF8A12469DE2');
        $this->addSql('ALTER TABLE affiliate_category DROP FOREIGN KEY FK_CEC6AF8A9F12C49A');
        $this->addSql('CREATE TABLE affiliates (id INT AUTO_INCREMENT NOT NULL, url VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, email VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, token VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_108C6A8F5F37A13B (token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE affiliates_categories (affiliate_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_87BE21809F12C49A (affiliate_id), INDEX IDX_87BE218012469DE2 (category_id), PRIMARY KEY(affiliate_id, category_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL COLLATE utf8mb4_unicode_ci, slug VARCHAR(128) NOT NULL COLLATE utf8mb4_unicode_ci, UNIQUE INDEX UNIQ_3AF34668989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE jobs (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, type VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, company VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, logo VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, url VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, position VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, location VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, description LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci, how_to_apply LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci, token VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, public TINYINT(1) NOT NULL, activated TINYINT(1) NOT NULL, email VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, expires_at DATETIME NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_A8936DC512469DE2 (category_id), UNIQUE INDEX UNIQ_A8936DC55F37A13B (token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE affiliates_categories ADD CONSTRAINT FK_87BE218012469DE2 FOREIGN KEY (category_id) REFERENCES categories (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE affiliates_categories ADD CONSTRAINT FK_87BE21809F12C49A FOREIGN KEY (affiliate_id) REFERENCES affiliates (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jobs ADD CONSTRAINT FK_A8936DC512469DE2 FOREIGN KEY (category_id) REFERENCES categories (id)');
        $this->addSql('DROP TABLE job');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE affiliate');
        $this->addSql('DROP TABLE affiliate_category');
    }
}
