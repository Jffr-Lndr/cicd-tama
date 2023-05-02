<?php

require_once $_SERVER['DOCUMENT_ROOT'].'\Database.php';

Database::use('cicd_tama');

Database::rawQuery('CALL CREATE_ACCOUNT("'.$_POST["accountName"].'")');

header("location: /");