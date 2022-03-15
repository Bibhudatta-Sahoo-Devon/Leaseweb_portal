<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220310100742 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE server (id INT AUTO_INCREMENT NOT NULL, ram_id INT NOT NULL, hdd_id INT NOT NULL, location_id INT NOT NULL, model VARCHAR(255) NOT NULL, price VARCHAR(255) NOT NULL, INDEX IDX_5A6DD5F63366068 (ram_id), INDEX IDX_5A6DD5F61493816F (hdd_id), INDEX IDX_5A6DD5F664D218E (location_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE server ADD CONSTRAINT FK_5A6DD5F63366068 FOREIGN KEY (ram_id) REFERENCES ram (id)');
        $this->addSql('ALTER TABLE server ADD CONSTRAINT FK_5A6DD5F61493816F FOREIGN KEY (hdd_id) REFERENCES harddisk (id)');
        $this->addSql('ALTER TABLE server ADD CONSTRAINT FK_5A6DD5F664D218E FOREIGN KEY (location_id) REFERENCES location (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE server');
    }
}
