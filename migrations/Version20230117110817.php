<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230117110817 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vinilo DROP FOREIGN KEY FK_702172BEB3A4FC4');
        $this->addSql('DROP INDEX IDX_702172BEB3A4FC4 ON vinilo');
        $this->addSql('ALTER TABLE vinilo CHANGE id_artista_id artista_id INT NOT NULL');
        $this->addSql('ALTER TABLE vinilo ADD CONSTRAINT FK_702172BEAEB0CF13 FOREIGN KEY (artista_id) REFERENCES artista (id)');
        $this->addSql('CREATE INDEX IDX_702172BEAEB0CF13 ON vinilo (artista_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vinilo DROP FOREIGN KEY FK_702172BEAEB0CF13');
        $this->addSql('DROP INDEX IDX_702172BEAEB0CF13 ON vinilo');
        $this->addSql('ALTER TABLE vinilo CHANGE artista_id id_artista_id INT NOT NULL');
        $this->addSql('ALTER TABLE vinilo ADD CONSTRAINT FK_702172BEB3A4FC4 FOREIGN KEY (id_artista_id) REFERENCES artista (id)');
        $this->addSql('CREATE INDEX IDX_702172BEB3A4FC4 ON vinilo (id_artista_id)');
    }
}
