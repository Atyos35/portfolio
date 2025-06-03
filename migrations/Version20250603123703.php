<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250603123703 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\MySQL80Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\MySQL80Platform'."
        );

        $this->addSql('CREATE TABLE block (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, title VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, names JSON NOT NULL, position INT NOT NULL, INDEX IDX_831B9722A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\MySQL80Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\MySQL80Platform'."
        );

        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, roles JSON NOT NULL, password VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, firstname VARCHAR(50) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, lastname VARCHAR(50) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, job VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, linkedin VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, age VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, city VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, phone VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, is_verified TINYINT(1) NOT NULL, profile_picture VARCHAR(255) CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_unicode_ci`, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\MySQL80Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\MySQL80Platform'."
        );

        $this->addSql('CREATE TABLE training (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(44) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, description VARCHAR(43) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, start_date DATE NOT NULL, end_date DATE DEFAULT NULL, duration VARCHAR(255) CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_unicode_ci` COMMENT \'(DC2Type:dateinterval)\', city VARCHAR(15) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, school VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, INDEX IDX_D5128A8FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\MySQL80Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\MySQL80Platform'."
        );

        $this->addSql('CREATE TABLE experience (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(42) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, start_date DATE NOT NULL, end_date DATE DEFAULT NULL, description VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, duration VARCHAR(255) CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_unicode_ci` COMMENT \'(DC2Type:dateinterval)\', company VARCHAR(50) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, city VARCHAR(15) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, INDEX IDX_590C103A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\MySQL80Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\MySQL80Platform'."
        );

        $this->addSql('DROP TABLE block');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\MySQL80Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\MySQL80Platform'."
        );

        $this->addSql('DROP TABLE user');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\MySQL80Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\MySQL80Platform'."
        );

        $this->addSql('DROP TABLE training');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\MySQL80Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\MySQL80Platform'."
        );

        $this->addSql('DROP TABLE experience');
    }
}
