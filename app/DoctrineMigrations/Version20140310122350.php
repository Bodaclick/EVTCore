<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20140310122350 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql ("INSERT INTO vertical (domain) values ('cumpleclick.com');
                        INSERT INTO vertical (domain) values ('fiestaclick.es');
                        INSERT INTO vertical (domain) values ('celebracionclick.com');
                        INSERT INTO vertical (domain) values ('espacioclick.com');
                        INSERT INTO vertical (domain) values ('eventoclick.com');
                        INSERT INTO vertical (domain) values ('cumpleclick.com.mx');
                        INSERT INTO vertical (domain) values ('fiestaclick.mx');
                        INSERT INTO vertical (domain) values ('quinceclick.mx');
                        INSERT INTO vertical (domain) values ('graduacionesclick.mx');
                        INSERT INTO vertical (domain) values ('espacioclick.com.mx');
                        INSERT INTO vertical (domain) values ('eventoclick.com.mx');
                        INSERT INTO vertical (domain) values ('aniversarioclick.com.br');
                        INSERT INTO vertical (domain) values ('festaclick.com.br');
                        INSERT INTO vertical (domain) values ('15anos.com.br');
                        INSERT INTO vertical (domain) values ('formaturaclick.com.br');
                        INSERT INTO vertical (domain) values ('buffetclick.com.br');
                        INSERT INTO vertical (domain) values ('eventoclick.com.br');
                        INSERT INTO vertical (domain) values ('festaclick.it');
                        INSERT INTO vertical (domain) values ('compleannoclick.it');
                        INSERT INTO vertical (domain) values ('batessimoecomunioneclick.it');
                        INSERT INTO vertical (domain) values ('eventoclick.it')");

    }

    public function down(Schema $schema)
    {

    }
}
