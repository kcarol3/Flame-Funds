<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231021203232 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE financial_goal (id INT AUTO_INCREMENT NOT NULL, account_id INT NOT NULL, name VARCHAR(255) NOT NULL, date_start DATETIME NOT NULL, date_end DATETIME NOT NULL, goal_amount NUMERIC(8, 2) NOT NULL, current_amount NUMERIC(8, 2) NOT NULL, details VARCHAR(512) DEFAULT NULL, is_deleted TINYINT(1) NOT NULL, INDEX IDX_2CB34D6A9B6B5FBA (account_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE periodic (id INT AUTO_INCREMENT NOT NULL, account_id INT NOT NULL, category_id INT NOT NULL, name VARCHAR(255) NOT NULL, amount NUMERIC(8, 2) NOT NULL, date_start DATETIME NOT NULL, date_end DATETIME NOT NULL, days INT NOT NULL, details VARCHAR(512) DEFAULT NULL, is_deleted TINYINT(1) NOT NULL, INDEX IDX_8E2BF0E89B6B5FBA (account_id), INDEX IDX_8E2BF0E812469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE financial_goal ADD CONSTRAINT FK_2CB34D6A9B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE periodic ADD CONSTRAINT FK_8E2BF0E89B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE periodic ADD CONSTRAINT FK_8E2BF0E812469DE2 FOREIGN KEY (category_id) REFERENCES expense_category (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE financial_goal DROP FOREIGN KEY FK_2CB34D6A9B6B5FBA');
        $this->addSql('ALTER TABLE periodic DROP FOREIGN KEY FK_8E2BF0E89B6B5FBA');
        $this->addSql('ALTER TABLE periodic DROP FOREIGN KEY FK_8E2BF0E812469DE2');
        $this->addSql('DROP TABLE financial_goal');
        $this->addSql('DROP TABLE periodic');
    }
}
