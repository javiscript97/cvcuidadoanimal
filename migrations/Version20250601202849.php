<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250601202849 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE anuncio (id INT AUTO_INCREMENT NOT NULL, vet_id_id INT NOT NULL, titulo VARCHAR(255) NOT NULL, contenido LONGTEXT NOT NULL, fecha DATETIME DEFAULT NULL, INDEX IDX_4B3BC0D4A122277E (vet_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE anuncio ADD CONSTRAINT FK_4B3BC0D4A122277E FOREIGN KEY (vet_id_id) REFERENCES veterinario (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE anuncio DROP FOREIGN KEY FK_4B3BC0D4A122277E');
        $this->addSql('DROP TABLE anuncio');
    }
}
