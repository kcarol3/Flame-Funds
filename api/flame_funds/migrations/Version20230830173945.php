<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230830173945 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE account_history ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE account_history ADD CONSTRAINT FK_EE916403A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EE916403A76ED395 ON account_history (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE account_history DROP FOREIGN KEY FK_EE916403A76ED395');
        $this->addSql('DROP INDEX UNIQ_EE916403A76ED395 ON account_history');
        $this->addSql('ALTER TABLE account_history DROP user_id');
    }
}
