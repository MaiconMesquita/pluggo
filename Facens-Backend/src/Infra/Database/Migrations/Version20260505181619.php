<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260505181619 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE spot_reviews (id INT AUTO_INCREMENT NOT NULL, spot_id INT NOT NULL, driver_id INT NOT NULL, rating INT NOT NULL, comment LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_239ADFA72DF1D37C (spot_id), INDEX IDX_239ADFA7C3423909 (driver_id), UNIQUE INDEX unique_driver_spot (driver_id, spot_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE spot_reviews ADD CONSTRAINT FK_239ADFA72DF1D37C FOREIGN KEY (spot_id) REFERENCES charge_spots (id)');
        $this->addSql('ALTER TABLE spot_reviews ADD CONSTRAINT FK_239ADFA7C3423909 FOREIGN KEY (driver_id) REFERENCES driver (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE spot_reviews DROP FOREIGN KEY FK_239ADFA72DF1D37C');
        $this->addSql('ALTER TABLE spot_reviews DROP FOREIGN KEY FK_239ADFA7C3423909');
        $this->addSql('DROP TABLE spot_reviews');
    }
}
