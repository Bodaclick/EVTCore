<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140401164506 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql(
            "UPDATE vertical SET lang = 'es_ES' WHERE RIGHT( domain, 2 ) = 'es';
             UPDATE vertical SET lang = 'es_MX' WHERE RIGHT( domain, 2 ) = 'mx';
             UPDATE vertical SET lang = 'pt_BR' WHERE RIGHT( domain, 2 ) = 'br';
             UPDATE vertical SET lang = 'pt_PT' WHERE RIGHT( domain, 2 ) = 'pt';
             UPDATE vertical SET lang = 'it_IT' WHERE RIGHT( domain, 2 ) = 'it';
             UPDATE vertical SET lang = 'es_PR' WHERE RIGHT( domain, 2 ) = 'pr';
             UPDATE vertical SET lang = 'es_DO' WHERE RIGHT( domain, 2 ) = 'do';
             UPDATE vertical SET lang = 'es_ES' WHERE domain = 'aniversarioclick.com';
             UPDATE vertical SET lang = 'pt_BR' WHERE domain = 'batizadoclick.com';
             UPDATE vertical SET lang = 'es_ES' WHERE domain = 'celebracionclick.com';
             UPDATE vertical SET lang = 'es_ES' WHERE domain = 'cumpleclick.com';
             UPDATE vertical SET lang = 'es_ES' WHERE domain = 'espacioclick.com';
             UPDATE vertical SET lang = 'es_ES' WHERE domain = 'eventoclick.com';
             UPDATE vertical SET lang = 'es_ES' WHERE domain = 'test.com';"
        );

    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
