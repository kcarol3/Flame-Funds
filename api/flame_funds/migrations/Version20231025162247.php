<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231025162247 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE expense_category ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE expense_category ADD CONSTRAINT FK_C02DDB38A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_C02DDB38A76ED395 ON expense_category (user_id)');
        $this->addSql('ALTER TABLE income_category ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE income_category ADD CONSTRAINT FK_2F2D922FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_2F2D922FA76ED395 ON income_category (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE expense_category DROP FOREIGN KEY FK_C02DDB38A76ED395');
        $this->addSql('DROP INDEX IDX_C02DDB38A76ED395 ON expense_category');
        $this->addSql('ALTER TABLE expense_category DROP user_id');
        $this->addSql('ALTER TABLE income_category DROP FOREIGN KEY FK_2F2D922FA76ED395');
        $this->addSql('DROP INDEX IDX_2F2D922FA76ED395 ON income_category');
        $this->addSql('ALTER TABLE income_category DROP user_id');
    }
}
