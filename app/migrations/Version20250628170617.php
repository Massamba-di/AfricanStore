<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250628170617 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE command_products (id INT AUTO_INCREMENT NOT NULL, commands_id INT NOT NULL, products_id INT NOT NULL, quantity INT NOT NULL, total_price NUMERIC(10, 2) NOT NULL, INDEX IDX_8A5683CBF7982617 (commands_id), INDEX IDX_8A5683CB6C8A81A9 (products_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE command_products ADD CONSTRAINT FK_8A5683CBF7982617 FOREIGN KEY (commands_id) REFERENCES commands (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE command_products ADD CONSTRAINT FK_8A5683CB6C8A81A9 FOREIGN KEY (products_id) REFERENCES products (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE command_products DROP FOREIGN KEY FK_8A5683CBF7982617
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE command_products DROP FOREIGN KEY FK_8A5683CB6C8A81A9
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE command_products
        SQL);
    }
}
