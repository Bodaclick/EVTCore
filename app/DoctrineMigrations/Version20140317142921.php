<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140317142921 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql(
            "UPDATE `showroom` SET `type`='2' WHERE `score`='1';"
        );
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
