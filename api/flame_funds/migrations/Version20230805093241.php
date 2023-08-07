<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230805093241 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE account (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, balance NUMERIC(10, 2) NOT NULL, name VARCHAR(255) NOT NULL, created_date DATETIME NOT NULL, is_deleted TINYINT(1) NOT NULL, INDEX IDX_7D3656A4A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE expense (id INT AUTO_INCREMENT NOT NULL, account_id INT NOT NULL, name VARCHAR(255) NOT NULL, date DATETIME NOT NULL, amount NUMERIC(8, 2) NOT NULL, details VARCHAR(512) DEFAULT NULL, is_deleted TINYINT(1) NOT NULL, INDEX IDX_2D3A8DA69B6B5FBA (account_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE income (id INT AUTO_INCREMENT NOT NULL, account_id INT NOT NULL, name VARCHAR(255) NOT NULL, date DATETIME NOT NULL, amount NUMERIC(8, 2) NOT NULL, details VARCHAR(512) DEFAULT NULL, is_deleted TINYINT(1) NOT NULL, INDEX IDX_3FA862D09B6B5FBA (account_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE account ADD CONSTRAINT FK_7D3656A4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE expense ADD CONSTRAINT FK_2D3A8DA69B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE income ADD CONSTRAINT FK_3FA862D09B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE user ADD is_deleted TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE account DROP FOREIGN KEY FK_7D3656A4A76ED395');
        $this->addSql('ALTER TABLE expense DROP FOREIGN KEY FK_2D3A8DA69B6B5FBA');
        $this->addSql('ALTER TABLE income DROP FOREIGN KEY FK_3FA862D09B6B5FBA');
        $this->addSql('DROP TABLE account');
        $this->addSql('DROP TABLE expense');
        $this->addSql('DROP TABLE income');
        $this->addSql('ALTER TABLE user DROP is_deleted');
    }
}
