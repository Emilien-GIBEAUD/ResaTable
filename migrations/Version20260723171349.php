<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260723171349 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pizza_service (id INT AUTO_INCREMENT NOT NULL, service_date DATE NOT NULL, template_id INT NOT NULL, INDEX IDX_467242985DA0FB8 (template_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE pizza_service_template (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, start_time TIME NOT NULL, end_time TIME NOT NULL, slot_duration_in_min INT NOT NULL, capacity_per_slot INT NOT NULL, is_active TINYINT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE pizza_service ADD CONSTRAINT FK_467242985DA0FB8 FOREIGN KEY (template_id) REFERENCES pizza_service_template (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pizza_service DROP FOREIGN KEY FK_467242985DA0FB8');
        $this->addSql('DROP TABLE pizza_service');
        $this->addSql('DROP TABLE pizza_service_template');
    }
}
