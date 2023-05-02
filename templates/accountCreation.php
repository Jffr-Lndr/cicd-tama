<?php

require_once $_SERVER['DOCUMENT_ROOT'].'\Database.php';

Database::use('tamagotchi_bcl');

Database::rawQuery('CALL CREATE_ACCOUNT("'.$_POST["accountName"].'")');

header("location: /");