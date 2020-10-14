<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200927054401 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE document_companies_type DROP FOREIGN KEY FK_1CD754AD193D05F3');
        $this->addSql('ALTER TABLE document_categories DROP FOREIGN KEY FK_9B30ED3EC33F7837');
        $this->addSql('ALTER TABLE document_companies_type DROP FOREIGN KEY FK_1CD754ADC33F7837');
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A7661232A4F');
        $this->addSql('DROP TABLE companies_type');
        $this->addSql('DROP TABLE document');
        $this->addSql('DROP TABLE document_categories');
        $this->addSql('DROP TABLE document_companies_type');
        $this->addSql('DROP TABLE document_type');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE companies_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE document (id INT AUTO_INCREMENT NOT NULL, document_type_id INT DEFAULT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, prix INT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_D8698A7661232A4F (document_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE document_categories (document_id INT NOT NULL, categories_id INT NOT NULL, INDEX IDX_9B30ED3EA21214B7 (categories_id), INDEX IDX_9B30ED3EC33F7837 (document_id), PRIMARY KEY(document_id, categories_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE document_companies_type (document_id INT NOT NULL, companies_type_id INT NOT NULL, INDEX IDX_1CD754AD193D05F3 (companies_type_id), INDEX IDX_1CD754ADC33F7837 (document_id), PRIMARY KEY(document_id, companies_type_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE document_type (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A7661232A4F FOREIGN KEY (document_type_id) REFERENCES document_type (id)');
        $this->addSql('ALTER TABLE document_categories ADD CONSTRAINT FK_9B30ED3EA21214B7 FOREIGN KEY (categories_id) REFERENCES categories (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE document_categories ADD CONSTRAINT FK_9B30ED3EC33F7837 FOREIGN KEY (document_id) REFERENCES document (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE document_companies_type ADD CONSTRAINT FK_1CD754AD193D05F3 FOREIGN KEY (companies_type_id) REFERENCES companies_type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE document_companies_type ADD CONSTRAINT FK_1CD754ADC33F7837 FOREIGN KEY (document_id) REFERENCES document (id) ON DELETE CASCADE');
    }
}
