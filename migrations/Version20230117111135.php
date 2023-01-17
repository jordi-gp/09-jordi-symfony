<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230117111135 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE valoracion DROP FOREIGN KEY FK_6D3DE0F41AD1136A');
        $this->addSql('ALTER TABLE valoracion DROP FOREIGN KEY FK_6D3DE0F4629AF449');
        $this->addSql('DROP INDEX IDX_6D3DE0F41AD1136A ON valoracion');
        $this->addSql('DROP INDEX IDX_6D3DE0F4629AF449 ON valoracion');
        $this->addSql('ALTER TABLE valoracion ADD vinilo_id INT NOT NULL, ADD usuario_id INT NOT NULL, DROP vinilo_id_id, DROP usuario_id_id');
        $this->addSql('ALTER TABLE valoracion ADD CONSTRAINT FK_6D3DE0F4F8E11D70 FOREIGN KEY (vinilo_id) REFERENCES vinilo (id)');
        $this->addSql('ALTER TABLE valoracion ADD CONSTRAINT FK_6D3DE0F4DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('CREATE INDEX IDX_6D3DE0F4F8E11D70 ON valoracion (vinilo_id)');
        $this->addSql('CREATE INDEX IDX_6D3DE0F4DB38439E ON valoracion (usuario_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE valoracion DROP FOREIGN KEY FK_6D3DE0F4F8E11D70');
        $this->addSql('ALTER TABLE valoracion DROP FOREIGN KEY FK_6D3DE0F4DB38439E');
        $this->addSql('DROP INDEX IDX_6D3DE0F4F8E11D70 ON valoracion');
        $this->addSql('DROP INDEX IDX_6D3DE0F4DB38439E ON valoracion');
        $this->addSql('ALTER TABLE valoracion ADD vinilo_id_id INT NOT NULL, ADD usuario_id_id INT NOT NULL, DROP vinilo_id, DROP usuario_id');
        $this->addSql('ALTER TABLE valoracion ADD CONSTRAINT FK_6D3DE0F41AD1136A FOREIGN KEY (vinilo_id_id) REFERENCES vinilo (id)');
        $this->addSql('ALTER TABLE valoracion ADD CONSTRAINT FK_6D3DE0F4629AF449 FOREIGN KEY (usuario_id_id) REFERENCES usuario (id)');
        $this->addSql('CREATE INDEX IDX_6D3DE0F41AD1136A ON valoracion (vinilo_id_id)');
        $this->addSql('CREATE INDEX IDX_6D3DE0F4629AF449 ON valoracion (usuario_id_id)');
    }
}
