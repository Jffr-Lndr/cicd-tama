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
require_once $_SERVER['DOCUMENT_ROOT'].'\Database.php';
if( !isset($_COOKIE["accountName"]) ){
    header("location: /");
}
Database::use('cicd_tama');
$MyTamagochiRoster = Database::rawQueryWithReturnAll(sprintf("SELECT * FROM tamagotchis WHERE account_id = '%s' AND IS_ALIVE(tamagotchis.id) = 0", $_COOKIE["accountID"]));
?>
<?php
include_once $_SERVER['DOCUMENT_ROOT'].'\templates\navbar.php'
?>
<table class="table">
    <thead>
        <tr>
            <th scope="col">Nom</th>
            <th scope="col">Faim</th>
            <th scope="col">Soif</th>
            <th scope="col">Fatigue</th>
            <th scope="col">Bonheur</th>
            <th scope="col">Date de naissance</th>
            <th scope="col">Niveau</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php
        if(isset($_COOKIE["funeral"])){
            echo sprintf("<tr><td style='color:red'>%s vien de Mourrir</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>", $_COOKIE["funeral"]);
        }
        unset($_COOKIE['funeral']); 
        ?>
        <?php
            foreach($MyTamagochiRoster as $tamagotchi){
                echo sprintf("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td><a href='TamagotchiLife.php?id=%s'>Détail du tamagotchi</a></td></tr>", $tamagotchi["name"],  $tamagotchi["hunger"], $tamagotchi["thirst"],$tamagotchi["sleep"], $tamagotchi["boredom"], $tamagotchi["birthdate"],$level = Database::rawQueryWithReturnAll("SELECT LEVEL_CHECK(".$tamagotchi["id"].")")[0]["LEVEL_CHECK(".$tamagotchi["id"].")"] ,  $tamagotchi["id"]);
            }
        ?>
        
    </tbody>
<table class="table">
</table>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>
</html>