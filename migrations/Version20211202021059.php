<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211202021059 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` ADD delivry_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F529939865461A12 FOREIGN KEY (delivry_id) REFERENCES delivry (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F529939865461A12 ON `order` (delivry_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F529939865461A12');
        $this->addSql('DROP INDEX UNIQ_F529939865461A12 ON `order`');
        $this->addSql('ALTER TABLE `order` DROP delivry_id');
    }
}
