<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use RuntimeException;
use TheCodingMachine\FluidSchema\TdbmFluidSchema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200424154558 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create users and reset_password_tokens tables.';
    }

    public function up(Schema $schema): void
    {
        $db = new TdbmFluidSchema($schema);

        $db->table('users')
            ->column('id')->guid()->primaryKey()->comment('@UUID')
            ->column('first_name')->string(255)->notNull()
            ->column('last_name')->string(255)->notNull()
            ->column('email')->string(255)->notNull()->unique()
            ->column('password')->string(255)->null()->default(null)
            ->column('locale')->string(2)->notNull()
            ->column('profile_picture')->string(255)->null()->default(null)
            ->column('role')->string(255)->notNull();

        $db->table('reset_password_tokens')
            ->column('id')->guid()->primaryKey()->comment('@UUID')
            ->column('user_id')->references('users')->notNull()->unique()
            ->column('token')->string(255)->notNull()->unique()
            ->column('valid_until')->datetimeImmutable()->notNull();
    }

    public function down(Schema $schema): void
    {
        throw new RuntimeException('Never rollback a migration!');
    }
}
