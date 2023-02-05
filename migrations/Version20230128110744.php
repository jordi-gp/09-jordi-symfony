<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230128110744 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE artista ADD CONSTRAINT FK_9B6AF156547E6DA9 FOREIGN KEY (discografica_id) REFERENCES discografica (id)');
        $this->addSql('CREATE INDEX IDX_9B6AF156547E6DA9 ON artista (discografica_id)');
        $this->addSql('ALTER TABLE vinilo CHANGE name name VARCHAR(50) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vinilo CHANGE name name VARCHAR(30) NOT NULL');
        $this->addSql('ALTER TABLE artista DROP FOREIGN KEY FK_9B6AF156547E6DA9');
        $this->addSql('DROP INDEX IDX_9B6AF156547E6DA9 ON artista');
    }
}
