<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240618163655 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE intention (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(25) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inventory (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, album_id INT NOT NULL, status_id INT DEFAULT NULL, intention_id INT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_B12D4A36A76ED395 (user_id), INDEX IDX_B12D4A361137ABCF (album_id), INDEX IDX_B12D4A366BF700BD (status_id), INDEX IDX_B12D4A368EFCA86C (intention_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE inventory ADD CONSTRAINT FK_B12D4A36A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE inventory ADD CONSTRAINT FK_B12D4A361137ABCF FOREIGN KEY (album_id) REFERENCES album (id)');
        $this->addSql('ALTER TABLE inventory ADD CONSTRAINT FK_B12D4A366BF700BD FOREIGN KEY (status_id) REFERENCES `condition` (id)');
        $this->addSql('ALTER TABLE inventory ADD CONSTRAINT FK_B12D4A368EFCA86C FOREIGN KEY (intention_id) REFERENCES intention (id)');
        $this->addSql('ALTER TABLE album DROP FOREIGN KEY FK_39986E435D83CC1');
        $this->addSql('ALTER TABLE album DROP FOREIGN KEY FK_39986E43A76ED395');
        $this->addSql('DROP INDEX IDX_39986E435D83CC1 ON album');
        $this->addSql('DROP INDEX IDX_39986E43A76ED395 ON album');
        $this->addSql('ALTER TABLE album DROP state_id, DROP user_id, DROP description, DROP created_at, CHANGE title title VARCHAR(120) NOT NULL, CHANGE artist artist VARCHAR(120) NOT NULL, CHANGE image image VARCHAR(120) NOT NULL, CHANGE year year INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inventory DROP FOREIGN KEY FK_B12D4A36A76ED395');
        $this->addSql('ALTER TABLE inventory DROP FOREIGN KEY FK_B12D4A361137ABCF');
        $this->addSql('ALTER TABLE inventory DROP FOREIGN KEY FK_B12D4A366BF700BD');
        $this->addSql('ALTER TABLE inventory DROP FOREIGN KEY FK_B12D4A368EFCA86C');
        $this->addSql('DROP TABLE intention');
        $this->addSql('DROP TABLE inventory');
        $this->addSql('ALTER TABLE album ADD state_id INT NOT NULL, ADD user_id INT NOT NULL, ADD description LONGTEXT DEFAULT NULL, ADD created_at DATETIME NOT NULL, CHANGE title title VARCHAR(255) NOT NULL, CHANGE artist artist VARCHAR(255) NOT NULL, CHANGE year year VARCHAR(4) DEFAULT NULL, CHANGE image image VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE album ADD CONSTRAINT FK_39986E435D83CC1 FOREIGN KEY (state_id) REFERENCES `condition` (id)');
        $this->addSql('ALTER TABLE album ADD CONSTRAINT FK_39986E43A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_39986E435D83CC1 ON album (state_id)');
        $this->addSql('CREATE INDEX IDX_39986E43A76ED395 ON album (user_id)');
    }
}
