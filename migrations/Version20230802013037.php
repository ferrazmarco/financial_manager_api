<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230802013037 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE expense (id INT AUTO_INCREMENT NOT NULL, created_by_id INT NOT NULL, group__id INT NOT NULL, value INT NOT NULL, description VARCHAR(255) NOT NULL, created DATETIME NOT NULL, INDEX IDX_2D3A8DA6B03A8386 (created_by_id), INDEX IDX_2D3A8DA6E5D32D49 (group__id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE expense ADD CONSTRAINT FK_2D3A8DA6B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE expense ADD CONSTRAINT FK_2D3A8DA6E5D32D49 FOREIGN KEY (group__id) REFERENCES `group` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE expense DROP FOREIGN KEY FK_2D3A8DA6B03A8386');
        $this->addSql('ALTER TABLE expense DROP FOREIGN KEY FK_2D3A8DA6E5D32D49');
        $this->addSql('DROP TABLE expense');
    }
}
