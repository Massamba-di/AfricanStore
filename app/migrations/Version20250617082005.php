<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250617082005 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE commands ADD adresse_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commands ADD CONSTRAINT FK_9A3E132C4DE7DC5C FOREIGN KEY (adresse_id) REFERENCES adresse (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_9A3E132C4DE7DC5C ON commands (adresse_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE commands DROP FOREIGN KEY FK_9A3E132C4DE7DC5C
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_9A3E132C4DE7DC5C ON commands
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commands DROP adresse_id
        SQL);
    }
}
