<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140407111535 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql(
            "UPDATE vertical SET timezone = 'Europe/Madrid' WHERE lang = 'es_ES';
             UPDATE vertical SET timezone = 'America/Mexico_City' WHERE lang = 'es_MX';
             UPDATE vertical SET timezone = 'Europe/Rome' WHERE lang = 'it_IT';
             UPDATE vertical SET timezone = 'America/Sao_Paulo' WHERE lang = 'pt_BR';
             UPDATE vertical SET timezone = 'Europe/Lisbon' WHERE lang = 'pt_PT';"
        );
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
