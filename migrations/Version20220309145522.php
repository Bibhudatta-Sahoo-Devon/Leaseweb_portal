<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220309145522 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE filters (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(125) NOT NULL, type VARCHAR(125) NOT NULL, value VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE harddisk (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(125) NOT NULL, size VARCHAR(125) NOT NULL, type VARCHAR(125) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ram (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, size VARCHAR(125) NOT NULL, type VARCHAR(125) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE servers (id INT AUTO_INCREMENT NOT NULL, model VARCHAR(255) NOT NULL, ram VARCHAR(125) NOT NULL, hdd VARCHAR(125) NOT NULL, location VARCHAR(255) NOT NULL, price VARCHAR(125) NOT NULL, INDEX ram_search (ram), INDEX hdd_search (hdd), INDEX location_search (location), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE filters');
        $this->addSql('DROP TABLE harddisk');
        $this->addSql('DROP TABLE ram');
        $this->addSql('DROP TABLE servers');
    }
}
