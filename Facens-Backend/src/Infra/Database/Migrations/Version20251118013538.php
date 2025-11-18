<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251118013538 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE charge_spot (id INT AUTO_INCREMENT NOT NULL, host_id INT NOT NULL, model VARCHAR(255) DEFAULT NULL, latitude VARCHAR(100) NOT NULL, longitude VARCHAR(100) NOT NULL, pricePerKwh DOUBLE PRECISION DEFAULT NULL, reviews JSON DEFAULT NULL, connectorType VARCHAR(50) DEFAULT NULL, status VARCHAR(20) NOT NULL, deactivationDate DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_F1F117AC1FB8D185 (host_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE charge_spot ADD CONSTRAINT FK_F1F117AC1FB8D185 FOREIGN KEY (host_id) REFERENCES host (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE charge_spots DROP FOREIGN KEY FK_C49FD1DF1FB8D185');
        $this->addSql('ALTER TABLE charge_spots ADD request_origin_id INT NOT NULL, DROP connectorType, CHANGE latitude latitude VARCHAR(255) NOT NULL, CHANGE longitude longitude VARCHAR(255) NOT NULL, CHANGE status status VARCHAR(255) NOT NULL, CHANGE pricePerKwh price_per_kwh DOUBLE PRECISION DEFAULT NULL, CHANGE model connector_type VARCHAR(255) DEFAULT NULL, CHANGE deactivationDate deactivation_date DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE charge_spots ADD CONSTRAINT FK_C49FD1DF1FB8D185 FOREIGN KEY (host_id) REFERENCES host (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE charge_spot DROP FOREIGN KEY FK_F1F117AC1FB8D185');
        $this->addSql('DROP TABLE charge_spot');
        $this->addSql('ALTER TABLE charge_spots DROP FOREIGN KEY FK_C49FD1DF1FB8D185');
        $this->addSql('ALTER TABLE charge_spots ADD connectorType VARCHAR(50) DEFAULT NULL, DROP request_origin_id, CHANGE latitude latitude VARCHAR(100) NOT NULL, CHANGE longitude longitude VARCHAR(100) NOT NULL, CHANGE status status VARCHAR(20) NOT NULL, CHANGE connector_type model VARCHAR(255) DEFAULT NULL, CHANGE price_per_kwh pricePerKwh DOUBLE PRECISION DEFAULT NULL, CHANGE deactivation_date deactivationDate DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE charge_spots ADD CONSTRAINT FK_C49FD1DF1FB8D185 FOREIGN KEY (host_id) REFERENCES host (id) ON UPDATE NO ACTION ON DELETE CASCADE');
    }
}
