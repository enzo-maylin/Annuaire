<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240924111557 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user CHANGE login login VARCHAR(32) NOT NULL, CHANGE mobile_number mobile_number VARCHAR(24) DEFAULT NULL, CHANGE landline_number landline_number VARCHAR(24) DEFAULT NULL, CHANGE code code VARCHAR(32) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user CHANGE login login VARCHAR(180) NOT NULL, CHANGE mobile_number mobile_number VARCHAR(16) DEFAULT NULL, CHANGE landline_number landline_number VARCHAR(16) DEFAULT NULL, CHANGE code code VARCHAR(20) NOT NULL');
    }
}
