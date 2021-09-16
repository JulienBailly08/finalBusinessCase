<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210916130137 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE quantity_on_command DROP FOREIGN KEY FK_189860F733E1689A');
        $this->addSql('DROP TABLE command');
        $this->addSql('DROP TABLE quantity_on_command');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE command (id INT AUTO_INCREMENT NOT NULL, status_id INT NOT NULL, client_id INT NOT NULL, order_date DATETIME NOT NULL, click_and_collect TINYINT(1) NOT NULL, payment VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_8ECAEAD419EB6921 (client_id), INDEX IDX_8ECAEAD46BF700BD (status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE quantity_on_command (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, command_id INT NOT NULL, quantity INT NOT NULL, INDEX IDX_189860F733E1689A (command_id), INDEX IDX_189860F74584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE command ADD CONSTRAINT FK_8ECAEAD419EB6921 FOREIGN KEY (client_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE command ADD CONSTRAINT FK_8ECAEAD46BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('ALTER TABLE quantity_on_command ADD CONSTRAINT FK_189860F733E1689A FOREIGN KEY (command_id) REFERENCES command (id)');
        $this->addSql('ALTER TABLE quantity_on_command ADD CONSTRAINT FK_189860F74584665A FOREIGN KEY (product_id) REFERENCES product (id)');
    }
}
