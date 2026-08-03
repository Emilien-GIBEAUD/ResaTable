<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260803180026 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pizza_service_slot ADD service_id INT NOT NULL');
        $this->addSql('ALTER TABLE pizza_service_slot ADD CONSTRAINT FK_D016F8A6ED5CA9E6 FOREIGN KEY (service_id) REFERENCES pizza_service (id)');
        $this->addSql('CREATE INDEX IDX_D016F8A6ED5CA9E6 ON pizza_service_slot (service_id)');
        $this->addSql('ALTER TABLE reservation ADD slot_id INT NOT NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495559E5119C FOREIGN KEY (slot_id) REFERENCES pizza_service_slot (id)');
        $this->addSql('CREATE INDEX IDX_42C8495559E5119C ON reservation (slot_id)');
        $this->addSql('ALTER TABLE reservation_item ADD reservation_id INT NOT NULL');
        $this->addSql('ALTER TABLE reservation_item ADD CONSTRAINT FK_922E876B83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id)');
        $this->addSql('CREATE INDEX IDX_922E876B83297E7 ON reservation_item (reservation_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pizza_service_slot DROP FOREIGN KEY FK_D016F8A6ED5CA9E6');
        $this->addSql('DROP INDEX IDX_D016F8A6ED5CA9E6 ON pizza_service_slot');
        $this->addSql('ALTER TABLE pizza_service_slot DROP service_id');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495559E5119C');
        $this->addSql('DROP INDEX IDX_42C8495559E5119C ON reservation');
        $this->addSql('ALTER TABLE reservation DROP slot_id');
        $this->addSql('ALTER TABLE reservation_item DROP FOREIGN KEY FK_922E876B83297E7');
        $this->addSql('DROP INDEX IDX_922E876B83297E7 ON reservation_item');
        $this->addSql('ALTER TABLE reservation_item DROP reservation_id');
    }
}
