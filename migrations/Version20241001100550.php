<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241001100550 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE booking ADD benutzer_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_8038D4D89F9AC274 FOREIGN KEY (benutzer_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_8038D4D89F9AC274 ON booking (benutzer_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_8038D4D89F9AC274');
        $this->addSql('DROP INDEX IDX_8038D4D89F9AC274 ON booking');
        $this->addSql('ALTER TABLE booking DROP benutzer_id');
    }
}
