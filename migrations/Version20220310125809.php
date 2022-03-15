<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220310125809 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4C855C6F5E237E06 ON harddisk (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5E9E89CBD4E6F81 ON location (address)');
        $this->addSql('ALTER TABLE server RENAME INDEX idx_5a6dd5f63366068 TO ram_search');
        $this->addSql('ALTER TABLE server RENAME INDEX idx_5a6dd5f61493816f TO hdd_search');
        $this->addSql('ALTER TABLE server RENAME INDEX idx_5a6dd5f664d218e TO location_search');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_4C855C6F5E237E06 ON harddisk');
        $this->addSql('DROP INDEX UNIQ_5E9E89CBD4E6F81 ON location');
        $this->addSql('ALTER TABLE server RENAME INDEX hdd_search TO IDX_5A6DD5F61493816F');
        $this->addSql('ALTER TABLE server RENAME INDEX ram_search TO IDX_5A6DD5F63366068');
        $this->addSql('ALTER TABLE server RENAME INDEX location_search TO IDX_5A6DD5F664D218E');
    }
}
