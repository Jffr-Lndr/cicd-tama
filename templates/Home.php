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
        Database::use('tamagotchi_bcl');
        if( isset($_POST["accountName"]) ){
            // DO DROP TABLE HERE FOR MAX FUN
            if(Database::rawQueryWithReturnOne(sprintf("SELECT COUNT(*) FROM accounts WHERE name = '%s'", $_POST["accountName"]))[0] > 0){
                setcookie("accountName", $_POST["accountName"]);
                setcookie("accountID", Database::rawQueryWithReturnOne(sprintf("SELECT id FROM accounts WHERE name = '%s'", $_POST["accountName"]))["id"]);
            }
        }
    Database::use('tamagotchi_bcl');
    $MyTamagochiRoster = Database::rawQueryWithReturnAll(sprintf("SELECT * FROM tamagotchis WHERE account_id = '%s'", $_COOKIE["accountID"]));

    ?>
    <?php
        include_once $_SERVER['DOCUMENT_ROOT'].'\templates\navbar.php'
    ?>
    <form action="TamagotchiAddUser.php" method="post">
        Nom du Tamagotchi: <input type="text" name="tamagotchiName"><br>
        <input type="submit" value="Je crée mon tamagotchi">
    </form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>
</html>