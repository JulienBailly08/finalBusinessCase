<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210731142657 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE quantity_on_command ADD product_id INT NOT NULL, ADD command_id INT NOT NULL');
        $this->addSql('ALTER TABLE quantity_on_command ADD CONSTRAINT FK_189860F74584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE quantity_on_command ADD CONSTRAINT FK_189860F733E1689A FOREIGN KEY (command_id) REFERENCES command (id)');
        $this->addSql('CREATE INDEX IDX_189860F74584665A ON quantity_on_command (product_id)');
        $this->addSql('CREATE INDEX IDX_189860F733E1689A ON quantity_on_command (command_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE quantity_on_command DROP FOREIGN KEY FK_189860F74584665A');
        $this->addSql('ALTER TABLE quantity_on_command DROP FOREIGN KEY FK_189860F733E1689A');
        $this->addSql('DROP INDEX IDX_189860F74584665A ON quantity_on_command');
        $this->addSql('DROP INDEX IDX_189860F733E1689A ON quantity_on_command');
        $this->addSql('ALTER TABLE quantity_on_command DROP product_id, DROP command_id');
    }
}
