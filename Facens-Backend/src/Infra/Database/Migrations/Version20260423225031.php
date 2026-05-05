<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260423225031 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE employee (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, device_id VARCHAR(255) DEFAULT NULL, one_signal_id VARCHAR(255) DEFAULT NULL, cpf VARCHAR(11) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, phone VARCHAR(20) DEFAULT NULL, password VARCHAR(255) DEFAULT NULL, status TINYINT(1) NOT NULL, is_push_notification_enabled TINYINT(1) DEFAULT 1, password_attempt INT DEFAULT 0 NOT NULL, change_password TINYINT(1) DEFAULT 0, deactivation_date DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_5D9F75A1E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE charge_spot DROP FOREIGN KEY FK_F1F117AC1FB8D185');
        $this->addSql('DROP TABLE charge_spot');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE charge_spot (id INT AUTO_INCREMENT NOT NULL, host_id INT NOT NULL, model VARCHAR(255) CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_unicode_ci`, latitude VARCHAR(100) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, longitude VARCHAR(100) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, pricePerKwh DOUBLE PRECISION DEFAULT NULL, reviews JSON DEFAULT NULL, connectorType VARCHAR(50) CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_unicode_ci`, status VARCHAR(20) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, deactivationDate DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_F1F117AC1FB8D185 (host_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE charge_spot ADD CONSTRAINT FK_F1F117AC1FB8D185 FOREIGN KEY (host_id) REFERENCES host (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('DROP TABLE employee');
    }
}
