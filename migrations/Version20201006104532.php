<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201006104532 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_documents ADD document_id INT NOT NULL, ADD user_id INT NOT NULL, ADD company_id INT DEFAULT NULL, ADD created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE user_documents ADD CONSTRAINT FK_A250FF6CC33F7837 FOREIGN KEY (document_id) REFERENCES documents (id)');
        $this->addSql('ALTER TABLE user_documents ADD CONSTRAINT FK_A250FF6CA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE user_documents ADD CONSTRAINT FK_A250FF6C979B1AD6 FOREIGN KEY (company_id) REFERENCES companies (id)');
        $this->addSql('CREATE INDEX IDX_A250FF6CC33F7837 ON user_documents (document_id)');
        $this->addSql('CREATE INDEX IDX_A250FF6CA76ED395 ON user_documents (user_id)');
        $this->addSql('CREATE INDEX IDX_A250FF6C979B1AD6 ON user_documents (company_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_documents DROP FOREIGN KEY FK_A250FF6CC33F7837');
        $this->addSql('ALTER TABLE user_documents DROP FOREIGN KEY FK_A250FF6CA76ED395');
        $this->addSql('ALTER TABLE user_documents DROP FOREIGN KEY FK_A250FF6C979B1AD6');
        $this->addSql('DROP INDEX IDX_A250FF6CC33F7837 ON user_documents');
        $this->addSql('DROP INDEX IDX_A250FF6CA76ED395 ON user_documents');
        $this->addSql('DROP INDEX IDX_A250FF6C979B1AD6 ON user_documents');
        $this->addSql('ALTER TABLE user_documents DROP document_id, DROP user_id, DROP company_id, DROP created_at');
    }
}
