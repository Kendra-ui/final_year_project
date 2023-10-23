<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230624083715 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B7AF89CCED FOREIGN KEY (productid_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_BA388B7AF89CCED ON cart (productid_id)');
        $this->addSql('ALTER TABLE payment ADD productcart_id INT NOT NULL, ADD carts_id INT NOT NULL');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D819A6B98 FOREIGN KEY (productcart_id) REFERENCES product_cart (id)');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840DBCB5C6F5 FOREIGN KEY (carts_id) REFERENCES cart (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6D28840D819A6B98 ON payment (productcart_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6D28840DBCB5C6F5 ON payment (carts_id)');
        $this->addSql('ALTER TABLE problem ADD product_id INT NOT NULL');
        $this->addSql('ALTER TABLE problem ADD CONSTRAINT FK_D7E7CCC84584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_D7E7CCC84584665A ON problem (product_id)');
        $this->addSql('ALTER TABLE product_cart ADD product_id INT NOT NULL, ADD carts_id INT NOT NULL');
        $this->addSql('ALTER TABLE product_cart ADD CONSTRAINT FK_864BAA164584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_cart ADD CONSTRAINT FK_864BAA16BCB5C6F5 FOREIGN KEY (carts_id) REFERENCES cart (id)');
        $this->addSql('CREATE INDEX IDX_864BAA164584665A ON product_cart (product_id)');
        $this->addSql('CREATE INDEX IDX_864BAA16BCB5C6F5 ON product_cart (carts_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B7AF89CCED');
        $this->addSql('DROP INDEX IDX_BA388B7AF89CCED ON cart');
        $this->addSql('ALTER TABLE problem DROP FOREIGN KEY FK_D7E7CCC84584665A');
        $this->addSql('DROP INDEX IDX_D7E7CCC84584665A ON problem');
        $this->addSql('ALTER TABLE problem DROP product_id');
        $this->addSql('ALTER TABLE product_cart DROP FOREIGN KEY FK_864BAA164584665A');
        $this->addSql('ALTER TABLE product_cart DROP FOREIGN KEY FK_864BAA16BCB5C6F5');
        $this->addSql('DROP INDEX IDX_864BAA164584665A ON product_cart');
        $this->addSql('DROP INDEX IDX_864BAA16BCB5C6F5 ON product_cart');
        $this->addSql('ALTER TABLE product_cart DROP product_id, DROP carts_id');
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840D819A6B98');
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840DBCB5C6F5');
        $this->addSql('DROP INDEX UNIQ_6D28840D819A6B98 ON payment');
        $this->addSql('DROP INDEX UNIQ_6D28840DBCB5C6F5 ON payment');
        $this->addSql('ALTER TABLE payment DROP productcart_id, DROP carts_id');
    }
}
