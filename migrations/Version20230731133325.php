<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230731133325 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE currency (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', tag VARCHAR(180) NOT NULL, name VARCHAR(180) NOT NULL, is_crypto TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_6956883F389B783 (tag), INDEX idx_currency_tag (tag), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE currency_pair_exchange (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', base_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', quote_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', rate VARCHAR(180) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_940417896967DF41 (base_id), INDEX IDX_94041789DB805178 (quote_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE currency_pair_exchange ADD CONSTRAINT FK_940417896967DF41 FOREIGN KEY (base_id) REFERENCES currency (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE currency_pair_exchange ADD CONSTRAINT FK_94041789DB805178 FOREIGN KEY (quote_id) REFERENCES currency (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE currency_pair_exchange DROP FOREIGN KEY FK_940417896967DF41');
        $this->addSql('ALTER TABLE currency_pair_exchange DROP FOREIGN KEY FK_94041789DB805178');
        $this->addSql('DROP TABLE currency');
        $this->addSql('DROP TABLE currency_pair_exchange');
    }
}
