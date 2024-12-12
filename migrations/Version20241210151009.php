<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241210151009 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE booking (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, date DATE NOT NULL, start_time TIME NOT NULL, end_time TIME NOT NULL, floor INT NOT NULL, room INT NOT NULL, `table` INT NOT NULL, INDEX IDX_E00CEDDEA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_8038D4D89F9AC274');
        $this->addSql('DROP TABLE booking');
        $this->addSql('ALTER TABLE user ADD firstName VARCHAR(255) DEFAULT NULL, ADD last_name VARCHAR(255) DEFAULT NULL, ADD phone_number VARCHAR(255) DEFAULT NULL, ADD photo VARCHAR(255) DEFAULT NULL, DROP firstName, DROP lastName, DROP phoneNumber, DROP photo');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE booking (id INT AUTO_INCREMENT NOT NULL, benutzer_id INT DEFAULT NULL, datum DATE NOT NULL, startTime TIME NOT NULL, endTime TIME NOT NULL, floor INT NOT NULL, raum INT NOT NULL, tisch INT NOT NULL, INDEX IDX_8038D4D89F9AC274 (benutzer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_8038D4D89F9AC274 FOREIGN KEY (benutzer_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDEA76ED395');
        $this->addSql('DROP TABLE booking');
        $this->addSql('ALTER TABLE user ADD firstName VARCHAR(255) DEFAULT NULL, ADD lastName VARCHAR(255) DEFAULT NULL, ADD phoneNumber VARCHAR(255) DEFAULT NULL, ADD photo VARCHAR(255) DEFAULT NULL, DROP firstName, DROP last_name, DROP phone_number, DROP photo');
    }
}
