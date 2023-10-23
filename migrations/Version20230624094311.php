<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230624094311 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE problem ADD CONSTRAINT FK_D7E7CCC84584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_D7E7CCC84584665A ON problem (product_id)');
        $this->addSql('ALTER TABLE product ADD product_id INT NOT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD4584665A FOREIGN KEY (product_id) REFERENCES cart (id)');
        $this->addSql('CREATE INDEX IDX_D34A04AD4584665A ON product (product_id)');
        $this->addSql('ALTER TABLE product_cart ADD product_id INT NOT NULL, ADD carts_id INT NOT NULL');
        $this->addSql('ALTER TABLE product_cart ADD CONSTRAINT FK_864BAA164584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_cart ADD CONSTRAINT FK_864BAA16BCB5C6F5 FOREIGN KEY (carts_id) REFERENCES cart (id)');
        $this->addSql('CREATE INDEX IDX_864BAA164584665A ON product_cart (product_id)');
        $this->addSql('CREATE INDEX IDX_864BAA16BCB5C6F5 ON product_cart (carts_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE problem DROP FOREIGN KEY FK_D7E7CCC84584665A');
        $this->addSql('DROP INDEX IDX_D7E7CCC84584665A ON problem');
        $this->addSql('ALTER TABLE product_cart DROP FOREIGN KEY FK_864BAA164584665A');
        $this->addSql('ALTER TABLE product_cart DROP FOREIGN KEY FK_864BAA16BCB5C6F5');
        $this->addSql('DROP INDEX IDX_864BAA164584665A ON product_cart');
        $this->addSql('DROP INDEX IDX_864BAA16BCB5C6F5 ON product_cart');
        $this->addSql('ALTER TABLE product_cart DROP product_id, DROP carts_id');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD4584665A');
        $this->addSql('DROP INDEX IDX_D34A04AD4584665A ON product');
        $this->addSql('ALTER TABLE product DROP product_id');
    }
}
