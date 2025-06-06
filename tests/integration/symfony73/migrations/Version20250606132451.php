<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250606132451 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Test DB';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            CREATE TABLE test_entity (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, foo VARCHAR(255) NOT NULL)
        SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            DROP TABLE test_entity
        SQL);
    }
}
