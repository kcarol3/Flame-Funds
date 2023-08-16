<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230816110124 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE expense_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, details VARCHAR(255) DEFAULT NULL, is_deleted TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE income_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, details VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE expense ADD category_id INT NOT NULL');
        $this->addSql('ALTER TABLE expense ADD CONSTRAINT FK_2D3A8DA612469DE2 FOREIGN KEY (category_id) REFERENCES expense_category (id)');
        $this->addSql('CREATE INDEX IDX_2D3A8DA612469DE2 ON expense (category_id)');
        $this->addSql('ALTER TABLE income ADD category_id INT NOT NULL');
        $this->addSql('ALTER TABLE income ADD CONSTRAINT FK_3FA862D012469DE2 FOREIGN KEY (category_id) REFERENCES income_category (id)');
        $this->addSql('CREATE INDEX IDX_3FA862D012469DE2 ON income (category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE expense DROP FOREIGN KEY FK_2D3A8DA612469DE2');
        $this->addSql('ALTER TABLE income DROP FOREIGN KEY FK_3FA862D012469DE2');
        $this->addSql('DROP TABLE expense_category');
        $this->addSql('DROP TABLE income_category');
        $this->addSql('DROP INDEX IDX_3FA862D012469DE2 ON income');
        $this->addSql('ALTER TABLE income DROP category_id');
        $this->addSql('DROP INDEX IDX_2D3A8DA612469DE2 ON expense');
        $this->addSql('ALTER TABLE expense DROP category_id');
    }
}
