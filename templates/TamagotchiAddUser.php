<?php
require_once $_SERVER['DOCUMENT_ROOT'].'\Database.php';

Database::use('cicd_tama');
Database::rawQuery('CALL CREATE_TAMAGOTCHI("'.$_POST["tamagotchiName"].'",'.$_COOKIE['accountID'].')');

header("location: /templates/TamagotchiRoster.php");

?>