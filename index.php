<?php
// when installed via composer
require_once $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/Database.php';

Database::use("cicd_tama");

include $_SERVER['DOCUMENT_ROOT'].'/templates/Login.php';