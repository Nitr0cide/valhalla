<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200927065724 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9C6798DB');
        $this->addSql('DROP INDEX IDX_1483A5E9C6798DB ON users');
        $this->addSql('ALTER TABLE users DROP account_type_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE users ADD account_type_id INT NOT NULL');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9C6798DB FOREIGN KEY (account_type_id) REFERENCES account_type (id)');
        $this->addSql('CREATE INDEX IDX_1483A5E9C6798DB ON users (account_type_id)');
    }
}
