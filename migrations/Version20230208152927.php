<?php
declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Platforms\{MySqlPlatform, SqlitePlatform};
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230208152927 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        if ($this->connection->getDatabasePlatform() instanceof MySqlPlatform) {
            $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(100) NOT NULL, image VARCHAR(255) DEFAULT NULL, last_update DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE category_dossier (category_id INT NOT NULL, dossier_id INT NOT NULL, INDEX IDX_FA90A04912469DE2 (category_id), INDEX IDX_FA90A049611C0C56 (dossier_id), PRIMARY KEY(category_id, dossier_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, address VARCHAR(100) DEFAULT NULL, city VARCHAR(50) DEFAULT NULL, country VARCHAR(50) DEFAULT NULL, last_update DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE dossier (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, author_id INT NOT NULL, title VARCHAR(255) NOT NULL, status INT DEFAULT 0 NOT NULL, content LONGTEXT NOT NULL, INDEX IDX_3D48E03719EB6921 (client_id), created_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, last_update DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_3D48E037F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, roles VARCHAR(255) DEFAULT \'ROLE_COMMENTATOR\' NOT NULL COMMENT \'(DC2Type:simple_array)\', enabled TINYINT(1) DEFAULT 1 NOT NULL, last_update DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
            $this->addSql('ALTER TABLE category_dossier ADD CONSTRAINT FK_FA90A04912469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
            $this->addSql('ALTER TABLE category_dossier ADD CONSTRAINT FK_FA90A049611C0C56 FOREIGN KEY (dossier_id) REFERENCES dossier (id) ON DELETE CASCADE');
            $this->addSql('ALTER TABLE dossier ADD CONSTRAINT FK_3D48E03719EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
            $this->addSql('ALTER TABLE dossier ADD CONSTRAINT FK_3D48E037F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        } elseif ($this->connection->getDatabasePlatform() instanceof SqlitePlatform) {
            $this->addSql('CREATE TABLE category (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(100) NOT NULL, image VARCHAR(255) DEFAULT NULL, last_update DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL)');
            $this->addSql('CREATE TABLE category_dossier (category_id INTEGER NOT NULL, dossier_id INTEGER NOT NULL, PRIMARY KEY(category_id, dossier_id), CONSTRAINT FK_FA90A04912469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_FA90A049611C0C56 FOREIGN KEY (dossier_id) REFERENCES dossier (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
            $this->addSql('CREATE INDEX IDX_FA90A04912469DE2 ON category_dossier (category_id)');
            $this->addSql('CREATE INDEX IDX_FA90A049611C0C56 ON category_dossier (dossier_id)');
            $this->addSql('CREATE TABLE client (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, address VARCHAR(100) DEFAULT NULL, city VARCHAR(50) DEFAULT NULL, country VARCHAR(50) DEFAULT NULL, last_update DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL)');
            $this->addSql('CREATE TABLE dossier (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, client_id INTEGER NOT NULL, author_id INTEGER NOT NULL, title VARCHAR(255) NOT NULL, created_date DATETIME DEFAULT \'CURRENT_TIMESTAMP\' NOT NULL --(DC2Type:date_immutable)
        , status INTEGER DEFAULT 0 NOT NULL, content CLOB NOT NULL, last_update DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CONSTRAINT FK_3D48E03719EB6921 FOREIGN KEY (client_id) REFERENCES client (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_3D48E037F675F31B FOREIGN KEY (author_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
            $this->addSql('CREATE INDEX IDX_3D48E03719EB6921 ON dossier (client_id)');
            $this->addSql('CREATE INDEX IDX_3D48E037F675F31B ON dossier (author_id)');
            $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, roles CLOB DEFAULT \'ROLE_COMMENTATOR\' NOT NULL --(DC2Type:simple_array)
        , enabled BOOLEAN DEFAULT 1 NOT NULL, last_update DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL)');
        } else {
            $this->abortIf(true, 'Migration can only be executed safely on \'mysql\' or \'sqlite\'.');
        }
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        if ($this->connection->getDatabasePlatform() instanceof MySqlPlatform) {
            $this->addSql('ALTER TABLE category_dossier DROP FOREIGN KEY FK_FA90A04912469DE2');
            $this->addSql('ALTER TABLE category_dossier DROP FOREIGN KEY FK_FA90A049611C0C56');
            $this->addSql('ALTER TABLE dossier DROP FOREIGN KEY FK_3D48E03719EB6921');
            $this->addSql('ALTER TABLE dossier DROP FOREIGN KEY FK_3D48E037F675F31B');
            $this->addSql('DROP TABLE category');
            $this->addSql('DROP TABLE category_dossier');
            $this->addSql('DROP TABLE client');
            $this->addSql('DROP TABLE dossier');
            $this->addSql('DROP TABLE `user`');
        } elseif ($this->connection->getDatabasePlatform() instanceof SqlitePlatform) {
            $this->addSql('DROP TABLE category');
            $this->addSql('DROP TABLE category_dossier');
            $this->addSql('DROP TABLE client');
            $this->addSql('DROP TABLE dossier');
            $this->addSql('DROP TABLE `user`');
        } else {
            $this->abortIf(true, 'Migration can only be executed safely on \'mysql\' or \'sqlite\'.');
        }
    }
}
