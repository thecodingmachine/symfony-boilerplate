<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230208155959 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("INSERT INTO `user` (`id`, `email`, `roles`) VALUES (NULL, 'admin@tcm.com', '[]')");
        // this up() migration is auto-generated, please modify it to your needs

    }

    public function down(Schema $schema): void
    {
        $this->addSql("DELETE FROM user WHERE `user`.`email` = 'admin@tcm.com'");
        // this down() migration is auto-generated, please modify it to your needs

    }
}
