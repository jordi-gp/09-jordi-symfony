<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230117110623 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE artista (id INT AUTO_INCREMENT NOT NULL, id_discografica_id INT NOT NULL, name VARCHAR(30) NOT NULL, description VARCHAR(250) NOT NULL, photo VARCHAR(50) NOT NULL, INDEX IDX_9B6AF156BD20AE21 (id_discografica_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE discografica (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(30) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE genero (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(30) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE usuario (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(30) NOT NULL, username VARCHAR(30) NOT NULL, email VARCHAR(50) NOT NULL, password VARCHAR(30) NOT NULL, role VARCHAR(30) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE valoracion (id INT AUTO_INCREMENT NOT NULL, vinilo_id_id INT NOT NULL, usuario_id_id INT NOT NULL, rating DOUBLE PRECISION NOT NULL, INDEX IDX_6D3DE0F41AD1136A (vinilo_id_id), INDEX IDX_6D3DE0F4629AF449 (usuario_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vinilo (id INT AUTO_INCREMENT NOT NULL, id_artista_id INT NOT NULL, name VARCHAR(30) NOT NULL, price INT NOT NULL, cover VARCHAR(50) NOT NULL, description VARCHAR(250) NOT NULL, rating DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_702172BEB3A4FC4 (id_artista_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE artista ADD CONSTRAINT FK_9B6AF156BD20AE21 FOREIGN KEY (id_discografica_id) REFERENCES discografica (id)');
        $this->addSql('ALTER TABLE valoracion ADD CONSTRAINT FK_6D3DE0F41AD1136A FOREIGN KEY (vinilo_id_id) REFERENCES vinilo (id)');
        $this->addSql('ALTER TABLE valoracion ADD CONSTRAINT FK_6D3DE0F4629AF449 FOREIGN KEY (usuario_id_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE vinilo ADD CONSTRAINT FK_702172BEB3A4FC4 FOREIGN KEY (id_artista_id) REFERENCES artista (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE artista DROP FOREIGN KEY FK_9B6AF156BD20AE21');
        $this->addSql('ALTER TABLE valoracion DROP FOREIGN KEY FK_6D3DE0F41AD1136A');
        $this->addSql('ALTER TABLE valoracion DROP FOREIGN KEY FK_6D3DE0F4629AF449');
        $this->addSql('ALTER TABLE vinilo DROP FOREIGN KEY FK_702172BEB3A4FC4');
        $this->addSql('DROP TABLE artista');
        $this->addSql('DROP TABLE discografica');
        $this->addSql('DROP TABLE genero');
        $this->addSql('DROP TABLE usuario');
        $this->addSql('DROP TABLE valoracion');
        $this->addSql('DROP TABLE vinilo');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
