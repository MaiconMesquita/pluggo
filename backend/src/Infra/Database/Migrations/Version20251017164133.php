<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251017164133 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE api_key (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', description VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE car_model (id INT AUTO_INCREMENT NOT NULL, driver_id INT NOT NULL, model VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_83EF70EC3423909 (driver_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE charge_spots (id INT AUTO_INCREMENT NOT NULL, host_id INT NOT NULL, model VARCHAR(255) NOT NULL, latitude VARCHAR(100) NOT NULL, longitude VARCHAR(100) NOT NULL, pricePerKwh DOUBLE PRECISION DEFAULT NULL, reviews JSON DEFAULT NULL, connectorType VARCHAR(50) DEFAULT NULL, status VARCHAR(20) NOT NULL, createdAt DATETIME NOT NULL, deactivationDate DATETIME DEFAULT NULL, updatedAt DATETIME DEFAULT NULL, INDEX IDX_C49FD1DF1FB8D185 (host_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE driver (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, phone VARCHAR(20) DEFAULT NULL, latitude VARCHAR(255) DEFAULT NULL, longitude VARCHAR(255) DEFAULT NULL, password VARCHAR(255) DEFAULT NULL, one_signal_id VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_11667CD9E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE host (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, phone VARCHAR(20) NOT NULL, password VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, deactivation_date DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_CF2713FDE7927C74 (email), UNIQUE INDEX UNIQ_CF2713FD444F97DD (phone), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message_history (id INT AUTO_INCREMENT NOT NULL, entityType VARCHAR(255) NOT NULL, message VARCHAR(255) NOT NULL, entityId INT NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE token (id VARCHAR(255) NOT NULL, user_id INT DEFAULT NULL, employee_id INT DEFAULT NULL, establishment_id INT DEFAULT NULL, supplier_id INT DEFAULT NULL, code VARCHAR(255) DEFAULT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE car_model ADD CONSTRAINT FK_83EF70EC3423909 FOREIGN KEY (driver_id) REFERENCES driver (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE charge_spots ADD CONSTRAINT FK_C49FD1DF1FB8D185 FOREIGN KEY (host_id) REFERENCES host (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE car_model DROP FOREIGN KEY FK_83EF70EC3423909');
        $this->addSql('ALTER TABLE charge_spots DROP FOREIGN KEY FK_C49FD1DF1FB8D185');
        $this->addSql('DROP TABLE api_key');
        $this->addSql('DROP TABLE car_model');
        $this->addSql('DROP TABLE charge_spots');
        $this->addSql('DROP TABLE driver');
        $this->addSql('DROP TABLE host');
        $this->addSql('DROP TABLE message_history');
        $this->addSql('DROP TABLE token');
    }
}
