<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240624044311 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {

         $this->addSql("INSERT INTO producto (nombre, descripcion, stock, precio, caducidad) VALUES ('Milbermax', 'Medicamento desparasitario para infecciones con lombrices', 10, 11.49, '2029-01-09')");
         $this->addSql("INSERT INTO producto (nombre, descripcion, stock, precio, caducidad) VALUES ('Hyaloral', 'Prevención de displasia', 10, 30.88, '2028-03-13')");
         $this->addSql("INSERT INTO producto (nombre, descripcion, stock, precio, caducidad) VALUES ('Soludex', 'Desinfectante', 10, 13.73, '2026-05-24')");
         $this->addSql("INSERT INTO producto (nombre, descripcion, stock, precio, caducidad) VALUES ('Otoclean', 'Limpiador Ótico', 10, 17.67, '2028-11-21')");
         $this->addSql("INSERT INTO producto (nombre, descripcion, stock, precio, caducidad) VALUES ('Aceprovet', 'Sedante', 10, 14.99, '2030-06-14')");
         $this->addSql("INSERT INTO producto (nombre, descripcion, stock, precio, caducidad) VALUES ('Lagrimet Pro', 'Solución ocular que limpia y protege los ojos de los perros', 10, 10.35, '2027-06-02')");


        // this up() migration is auto-generated, please modify it to your needs
    /*    $this->addSql('CREATE TABLE administrador (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, mail VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, rol VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chat (id INT AUTO_INCREMENT NOT NULL, cliente_id INT NOT NULL, vet_id INT NOT NULL, contenido LONGTEXT NOT NULL, fecha DATETIME NOT NULL, INDEX IDX_659DF2AAACC9C364 (cliente_id), INDEX IDX_659DF2AAA122277E (vet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE citas (id INT AUTO_INCREMENT NOT NULL, cliente_id INT NOT NULL, vet_id INT NOT NULL, fecha DATETIME NOT NULL, descripcion LONGTEXT NOT NULL, tipo VARCHAR(255) NOT NULL, INDEX IDX_B88CF8E5ACC9C364 (cliente_id), INDEX IDX_B88CF8E5A122277E (vet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cliente (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, edad INT NOT NULL, direccion VARCHAR(255) NOT NULL, mail VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, rol VARCHAR(255) NOT NULL, telefono INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE historial (id INT AUTO_INCREMENT NOT NULL, mascota_id INT NOT NULL, vet_id INT NOT NULL, descripcion LONGTEXT NOT NULL, fecha DATE NOT NULL, INDEX IDX_269506526AF31262 (mascota_id), INDEX IDX_26950652A122277E (vet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mascotas (id INT AUTO_INCREMENT NOT NULL, cliente_id INT NOT NULL, nombre VARCHAR(255) NOT NULL, edad INT NOT NULL, raza VARCHAR(255) NOT NULL, animal VARCHAR(255) NOT NULL, genero VARCHAR(255) NOT NULL, ficha LONGTEXT NOT NULL, INDEX IDX_D57E0219ACC9C364 (cliente_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE producto (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, descripcion VARCHAR(255) NOT NULL, stock INT NOT NULL, precio NUMERIC(10, 2) NOT NULL, caducidad DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE veterinario (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, edad INT NOT NULL, mail VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, rol VARCHAR(255) NOT NULL, especialidad VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AAACC9C364 FOREIGN KEY (cliente_id) REFERENCES cliente (id)');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AAA122277E FOREIGN KEY (vet_id) REFERENCES veterinario (id)');
        $this->addSql('ALTER TABLE citas ADD CONSTRAINT FK_B88CF8E5ACC9C364 FOREIGN KEY (cliente_id) REFERENCES cliente (id)');
        $this->addSql('ALTER TABLE citas ADD CONSTRAINT FK_B88CF8E5A122277E FOREIGN KEY (vet_id) REFERENCES veterinario (id)');
        $this->addSql('ALTER TABLE historial ADD CONSTRAINT FK_269506526AF31262 FOREIGN KEY (mascota_id) REFERENCES mascotas (id)');
        $this->addSql('ALTER TABLE historial ADD CONSTRAINT FK_26950652A122277E FOREIGN KEY (vet_id) REFERENCES veterinario (id)');
        $this->addSql('ALTER TABLE mascotas ADD CONSTRAINT FK_D57E0219ACC9C364 FOREIGN KEY (cliente_id) REFERENCES cliente (id)');*/
    }

    public function down(Schema $schema): void
    {
        /*
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chat DROP FOREIGN KEY FK_659DF2AAACC9C364');
        $this->addSql('ALTER TABLE chat DROP FOREIGN KEY FK_659DF2AAA122277E');
        $this->addSql('ALTER TABLE citas DROP FOREIGN KEY FK_B88CF8E5ACC9C364');
        $this->addSql('ALTER TABLE citas DROP FOREIGN KEY FK_B88CF8E5A122277E');
        $this->addSql('ALTER TABLE historial DROP FOREIGN KEY FK_269506526AF31262');
        $this->addSql('ALTER TABLE historial DROP FOREIGN KEY FK_26950652A122277E');
        $this->addSql('ALTER TABLE mascotas DROP FOREIGN KEY FK_D57E0219ACC9C364');
        $this->addSql('DROP TABLE administrador');
        $this->addSql('DROP TABLE chat');
        $this->addSql('DROP TABLE citas');
        $this->addSql('DROP TABLE cliente');
        $this->addSql('DROP TABLE historial');
        $this->addSql('DROP TABLE mascotas');
        $this->addSql('DROP TABLE producto');
        $this->addSql('DROP TABLE veterinario');
        $this->addSql('DROP TABLE messenger_messages');
        */
    }
}
