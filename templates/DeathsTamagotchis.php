<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>
<body>
<?php
include_once $_SERVER['DOCUMENT_ROOT'].'\templates\navbar.php';
require_once $_SERVER['DOCUMENT_ROOT'].'\Database.php';
Database::use('cicd_tama');

$MyDeadTamagochis = Database::rawQueryWithReturnAll(sprintf("SELECT tamagotchis.name,tamagotchis.birthdate,deaths.deathdate,deaths.id FROM tamagotchis JOIN accounts ON accounts.id = tamagotchis.account_id JOIN deaths ON deaths.tamagotchi_id = tamagotchis.id WHERE accounts.id = '%s'", $_COOKIE["accountID"]));
?>
<table class="table">
    <thead>
    <tr>
        <th scope="col">Nom des tamagotchis mort au combat</th>
        <th scope="col">Date de Naissance</th>
        <th scope="col">Date de Décès</th>
        <th scope="col">Niveau</th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach($MyDeadTamagochis as $tamagotchi){
        echo sprintf("<tr>
            <td>%s est mort pour ce projet</td>
            <td>Né le %s</td>
            <td>Mort le %s</td>
            <td>Niveau %s à sa mort</td>
            <td><a href='TamagotchiLife.php?id=%s'>Détail du tamagotchi</a></td>
        </tr>", 
        $tamagotchi["name"],
        $tamagotchi["birthdate"],
        $tamagotchi["deathdate"],
        $level = Database::rawQueryWithReturnAll("SELECT LEVEL_CHECK(".$tamagotchi["id"].")")[0]["LEVEL_CHECK(".$tamagotchi["id"].")"],
        $tamagotchi["id"]);
    }
    ?>
    </tbody>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>
</html>