<?php
// when installed via composer
//require_once 'vendor\autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'].'\Database.php';

Database::use("tamagotchi_bcl");

include $_SERVER['DOCUMENT_ROOT'].'\templates\Login.php';