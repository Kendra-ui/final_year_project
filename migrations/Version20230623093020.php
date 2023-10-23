<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230623093020 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE subscriber (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, skintype VARCHAR(255) NOT NULL, skinproblem VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP INDEX UNIQ_6D28840DCB0CD60F ON payment');
        $this->addSql('DROP INDEX UNIQ_6D28840D1AD5CDBF ON payment');
        $this->addSql('ALTER TABLE payment DROP product_cart_id, DROP cart_id, CHANGE amount amount INT NOT NULL, CHANGE email email LONGTEXT NOT NULL, CHANGE phone_number phone_number INT NOT NULL, CHANGE reference reference DOUBLE PRECISION NOT NULL, CHANGE status status VARCHAR(255) NOT NULL');
        $this->addSql('DROP INDEX IDX_D7E7CCC84584665A ON problem');
        $this->addSql('ALTER TABLE problem DROP product_id');
        $this->addSql('DROP INDEX IDX_B3BA5A5A1AD5CDBF ON product');
        $this->addSql('ALTER TABLE product DROP cart_id');
        $this->addSql('DROP INDEX IDX_864BAA164584665A ON product_cart');
        $this->addSql('DROP INDEX IDX_864BAA161AD5CDBF ON product_cart');
        $this->addSql('ALTER TABLE product_cart DROP product_id, DROP cart_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, skin_type VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, skin_problem VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE subscriber');
        $this->addSql('ALTER TABLE payment ADD product_cart_id INT DEFAULT NULL, ADD cart_id INT DEFAULT NULL, CHANGE amount amount DOUBLE PRECISION NOT NULL, CHANGE email email VARCHAR(255) NOT NULL, CHANGE phone_number phone_number VARCHAR(255) NOT NULL, CHANGE reference reference VARCHAR(255) NOT NULL, CHANGE status status TINYINT(1) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6D28840DCB0CD60F ON payment (product_cart_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6D28840D1AD5CDBF ON payment (cart_id)');
        $this->addSql('ALTER TABLE product_cart ADD product_id INT DEFAULT NULL, ADD cart_id INT DEFAULT NULL');
        $this->addSql('CREATE INDEX IDX_864BAA164584665A ON product_cart (product_id)');
        $this->addSql('CREATE INDEX IDX_864BAA161AD5CDBF ON product_cart (cart_id)');
        $this->addSql('ALTER TABLE product ADD cart_id INT DEFAULT NULL');
        $this->addSql('CREATE INDEX IDX_B3BA5A5A1AD5CDBF ON product (cart_id)');
        $this->addSql('ALTER TABLE problem ADD product_id INT DEFAULT NULL');
        $this->addSql('CREATE INDEX IDX_D7E7CCC84584665A ON problem (product_id)');
    }
}
