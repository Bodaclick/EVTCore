<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140331115455 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql(
            "UPDATE `provider` SET `lang`='es_ES' WHERE `location_country`='Spain';
             UPDATE `provider` SET `lang`='es_MX' WHERE `location_country`='Mexico';
             UPDATE `provider` SET `lang`='pt_BR' WHERE `location_country`='Brazil';
             UPDATE `provider` SET `lang`='pt_PT' WHERE `location_country`='Portugal';
             UPDATE `provider` SET `lang`='it_IT' WHERE `location_country`='Italy';
             UPDATE `provider` SET `lang`='es_PR' WHERE `location_country`='Puerto Rico';
             UPDATE `provider` SET `lang`='es_DO' WHERE `location_country`='Dominican Republic';"
        );

    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
