<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230201180839 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_save_vinil (usuario_id INT NOT NULL, vinilo_id INT NOT NULL, INDEX IDX_DE2CF2C0DB38439E (usuario_id), INDEX IDX_DE2CF2C0F8E11D70 (vinilo_id), PRIMARY KEY(usuario_id, vinilo_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_save_vinil ADD CONSTRAINT FK_DE2CF2C0DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_save_vinil ADD CONSTRAINT FK_DE2CF2C0F8E11D70 FOREIGN KEY (vinilo_id) REFERENCES vinilo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE usuario DROP FOREIGN KEY FK_2265B05DD7F5BCAA');
        $this->addSql('DROP INDEX IDX_2265B05DD7F5BCAA ON usuario');
        $this->addSql('ALTER TABLE usuario DROP saved_vinils_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_save_vinil DROP FOREIGN KEY FK_DE2CF2C0DB38439E');
        $this->addSql('ALTER TABLE user_save_vinil DROP FOREIGN KEY FK_DE2CF2C0F8E11D70');
        $this->addSql('DROP TABLE user_save_vinil');
        $this->addSql('ALTER TABLE usuario ADD saved_vinils_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE usuario ADD CONSTRAINT FK_2265B05DD7F5BCAA FOREIGN KEY (saved_vinils_id) REFERENCES vinilo (id)');
        $this->addSql('CREATE INDEX IDX_2265B05DD7F5BCAA ON usuario (saved_vinils_id)');
    }
}
