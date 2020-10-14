<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200927054820 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE companies_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE documents (id INT AUTO_INCREMENT NOT NULL, document_type_id INT NOT NULL, name VARCHAR(255) NOT NULL, prix INT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_A2B0728861232A4F (document_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE documents_categories (documents_id INT NOT NULL, categories_id INT NOT NULL, INDEX IDX_C62A17035F0F2752 (documents_id), INDEX IDX_C62A1703A21214B7 (categories_id), PRIMARY KEY(documents_id, categories_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE documents_companies_type (documents_id INT NOT NULL, companies_type_id INT NOT NULL, INDEX IDX_33255455F0F2752 (documents_id), INDEX IDX_3325545193D05F3 (companies_type_id), PRIMARY KEY(documents_id, companies_type_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE documents ADD CONSTRAINT FK_A2B0728861232A4F FOREIGN KEY (document_type_id) REFERENCES account_type (id)');
        $this->addSql('ALTER TABLE documents_categories ADD CONSTRAINT FK_C62A17035F0F2752 FOREIGN KEY (documents_id) REFERENCES documents (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE documents_categories ADD CONSTRAINT FK_C62A1703A21214B7 FOREIGN KEY (categories_id) REFERENCES categories (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE documents_companies_type ADD CONSTRAINT FK_33255455F0F2752 FOREIGN KEY (documents_id) REFERENCES documents (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE documents_companies_type ADD CONSTRAINT FK_3325545193D05F3 FOREIGN KEY (companies_type_id) REFERENCES companies_type (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE documents_companies_type DROP FOREIGN KEY FK_3325545193D05F3');
        $this->addSql('ALTER TABLE documents_categories DROP FOREIGN KEY FK_C62A17035F0F2752');
        $this->addSql('ALTER TABLE documents_companies_type DROP FOREIGN KEY FK_33255455F0F2752');
        $this->addSql('DROP TABLE companies_type');
        $this->addSql('DROP TABLE documents');
        $this->addSql('DROP TABLE documents_categories');
        $this->addSql('DROP TABLE documents_companies_type');
    }
}
