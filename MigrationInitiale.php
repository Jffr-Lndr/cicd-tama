<?php
// Début du code
require_once $_SERVER['DOCUMENT_ROOT'].'\Database.php';
require_once $_SERVER['DOCUMENT_ROOT'].'\Table.php';
require_once $_SERVER['DOCUMENT_ROOT'].'\Column.php';
//require_once 'Database.php';
//require_once 'Table.php';
//require_once 'Column.php';
if(Database::doesDatabaseExist("cicd_tama"))
{
    Database::dropDatabase("cicd_tama");
}

Database::migrate("cicd_tama", [
    new Table("accounts", "id", [
        new Column("id", "int unsigned", "not null auto_increment"),
        new Column("name", "varchar(255)", "not null unique"),
    ]),
    new Table("tamagotchis", "id", [
        new Column("id", "int unsigned", "not null auto_increment"),
        new Column("name", "varchar(255)", "not null"),
        new Column("hunger", "int unsigned", "not null default 70"),
        new Column("thirst", "int unsigned", "not null default 70"),
        new Column("sleep", "int unsigned", "not null default 70"),
        new Column("boredom", "int unsigned", "not null default 70"),
        new Column("birthdate", "datetime", "not null default current_timestamp"),
        new Column("account_id", "int unsigned", "not null"),
    ]),
    new Table("actions", "id", [
        new Column("id", "int unsigned", "not null auto_increment"),
        new Column("tamagotchi_id", "int unsigned", "not null"),
        new Column("name", "varchar(255)", "not null"),
    ]),
    new Table("deaths","id", [
        new Column("id", "int unsigned", "not null auto_increment"),
        new Column("tamagotchi_id", "int unsigned unique", "not null"),
        new Column("deathdate", "datetime", "not null default current_timestamp"),
    ])
]);