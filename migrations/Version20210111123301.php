<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210111123301 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comments ADD author_id INT NOT NULL');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_5F9E962AF675F31B ON comments (author_id)');
        $this->addSql('ALTER TABLE tricks ADD author_id INT NOT NULL');
        $this->addSql('ALTER TABLE tricks ADD CONSTRAINT FK_E1D902C1F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_E1D902C1F675F31B ON tricks (author_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `comments` DROP FOREIGN KEY FK_5F9E962AF675F31B');
        $this->addSql('DROP INDEX IDX_5F9E962AF675F31B ON `comments`');
        $this->addSql('ALTER TABLE `comments` DROP author_id');
        $this->addSql('ALTER TABLE `tricks` DROP FOREIGN KEY FK_E1D902C1F675F31B');
        $this->addSql('DROP INDEX IDX_E1D902C1F675F31B ON `tricks`');
        $this->addSql('ALTER TABLE `tricks` DROP author_id');
    }
}