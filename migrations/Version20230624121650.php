<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230624121650 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart ADD session INT NOT NULL, CHANGE session_id payment_id INT NOT NULL');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B74C3A3BB FOREIGN KEY (payment_id) REFERENCES payment (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BA388B74C3A3BB ON cart (payment_id)');
        $this->addSql('ALTER TABLE payment CHANGE amount amount DOUBLE PRECISION NOT NULL, CHANGE email email VARCHAR(255) NOT NULL, CHANGE reference reference VARCHAR(255) DEFAULT NULL, CHANGE status status VARCHAR(255) DEFAULT NULL, CHANGE phone_number phonenumber INT NOT NULL');
        $this->addSql('ALTER TABLE problem ADD product_id INT NOT NULL, DROP tag');
        $this->addSql('ALTER TABLE problem ADD CONSTRAINT FK_D7E7CCC84584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_D7E7CCC84584665A ON problem (product_id)');
        $this->addSql('ALTER TABLE product DROP type');
        $this->addSql('ALTER TABLE product_cart ADD cart_id INT NOT NULL, ADD product_id INT NOT NULL');
        $this->addSql('ALTER TABLE product_cart ADD CONSTRAINT FK_864BAA161AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id)');
        $this->addSql('ALTER TABLE product_cart ADD CONSTRAINT FK_864BAA164584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_864BAA161AD5CDBF ON product_cart (cart_id)');
        $this->addSql('CREATE INDEX IDX_864BAA164584665A ON product_cart (product_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B74C3A3BB');
        $this->addSql('DROP INDEX UNIQ_BA388B74C3A3BB ON cart');
        $this->addSql('ALTER TABLE cart ADD session_id INT NOT NULL, DROP payment_id, DROP session');
        $this->addSql('ALTER TABLE payment CHANGE email email LONGTEXT NOT NULL, CHANGE reference reference DOUBLE PRECISION NOT NULL, CHANGE status status VARCHAR(255) NOT NULL, CHANGE amount amount INT NOT NULL, CHANGE phonenumber phone_number INT NOT NULL');
        $this->addSql('ALTER TABLE problem DROP FOREIGN KEY FK_D7E7CCC84584665A');
        $this->addSql('DROP INDEX IDX_D7E7CCC84584665A ON problem');
        $this->addSql('ALTER TABLE problem ADD tag VARCHAR(255) NOT NULL, DROP product_id');
        $this->addSql('ALTER TABLE product ADD type VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE product_cart DROP FOREIGN KEY FK_864BAA161AD5CDBF');
        $this->addSql('ALTER TABLE product_cart DROP FOREIGN KEY FK_864BAA164584665A');
        $this->addSql('DROP INDEX IDX_864BAA161AD5CDBF ON product_cart');
        $this->addSql('DROP INDEX IDX_864BAA164584665A ON product_cart');
        $this->addSql('ALTER TABLE product_cart DROP cart_id, DROP product_id');
    }
}
