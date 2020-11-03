<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201031222126 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE factures (id INT AUTO_INCREMENT NOT NULL, categorie_id INT DEFAULT NULL, client_name VARCHAR(255) NOT NULL, emitted TINYINT(1) NOT NULL, prix_ht INT NOT NULL, prix_ttc INT DEFAULT NULL, file_path VARCHAR(255) NOT NULL, INDEX IDX_647590BBCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE factures_categories (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE factures ADD CONSTRAINT FK_647590BBCF5E72D FOREIGN KEY (categorie_id) REFERENCES factures_categories (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE factures DROP FOREIGN KEY FK_647590BBCF5E72D');
        $this->addSql('DROP TABLE factures');
        $this->addSql('DROP TABLE factures_categories');
    }
}
