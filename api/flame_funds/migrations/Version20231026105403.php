<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231026105403 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE periodic_details (id INT AUTO_INCREMENT NOT NULL, periodic_id INT NOT NULL, date DATETIME NOT NULL, amount NUMERIC(8, 2) NOT NULL, INDEX IDX_519815FC79B05C5B (periodic_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE periodic_details ADD CONSTRAINT FK_519815FC79B05C5B FOREIGN KEY (periodic_id) REFERENCES periodic (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE periodic_details DROP FOREIGN KEY FK_519815FC79B05C5B');
        $this->addSql('DROP TABLE periodic_details');
    }
}
