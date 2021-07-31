<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210731143137 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE command ADD status_id INT NOT NULL, ADD client_id INT NOT NULL');
        $this->addSql('ALTER TABLE command ADD CONSTRAINT FK_8ECAEAD46BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('ALTER TABLE command ADD CONSTRAINT FK_8ECAEAD419EB6921 FOREIGN KEY (client_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_8ECAEAD46BF700BD ON command (status_id)');
        $this->addSql('CREATE INDEX IDX_8ECAEAD419EB6921 ON command (client_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE command DROP FOREIGN KEY FK_8ECAEAD46BF700BD');
        $this->addSql('ALTER TABLE command DROP FOREIGN KEY FK_8ECAEAD419EB6921');
        $this->addSql('DROP INDEX IDX_8ECAEAD46BF700BD ON command');
        $this->addSql('DROP INDEX IDX_8ECAEAD419EB6921 ON command');
        $this->addSql('ALTER TABLE command DROP status_id, DROP client_id');
    }
}
