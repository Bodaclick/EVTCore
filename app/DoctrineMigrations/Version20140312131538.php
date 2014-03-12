<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20140312131538 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql(
            "INSERT INTO vertical (domain) values ('espacioclick.es');
            INSERT INTO vertical (domain) values ('fiestaclick.com.mx');
            INSERT INTO vertical (domain) values ('xvclick.com.mx');
            INSERT INTO vertical (domain) values ('migraduacionclick.com.mx');
            INSERT INTO vertical (domain) values ('festasclick.com.br');
            INSERT INTO vertical (domain) values ('15anosclick.com.br');
            INSERT INTO vertical (domain) values ('espacoeventoclick.com.br');
            INSERT INTO vertical (domain) values ('battesimoecomunioneclick.it');
            INSERT INTO vertical (domain) values ('laureaclick.it');
            INSERT INTO vertical (domain) values ('spazieventiclick.it');
            INSERT INTO vertical (domain) values ('aniversarioclick.com');
            INSERT INTO vertical (domain) values ('festaclick.pt');
            INSERT INTO vertical (domain) values ('batizadoclick.com');
            INSERT INTO vertical (domain) values ('espacoclick.pt');
            INSERT INTO vertical (domain) values ('eventoclick.pt');"
        );
    }

    public function down(Schema $schema)
    {
    }
}