<?php

require_once $_SERVER['DOCUMENT_ROOT'].'\Database.php';

Database::use('cicd_tama');

Database::rawQuery('CALL EAT("'.$_POST["tamagotchiID"].'")');

$checkAlive = Database::rawQueryWithReturnOne('SELECT IS_ALIVE("'.$_POST["tamagotchiID"].'")');

if( $checkAlive[0] > 0 ) {
    setcookie("funeral", Database::rawQueryWithReturnOne('SELECT name FROM tamagotchis WHERE tamagotchis.id = '.$_POST["tamagotchiID"])[0] ,time()+5, "/");
}

header("location: /templates/TamagotchiRoster.php");