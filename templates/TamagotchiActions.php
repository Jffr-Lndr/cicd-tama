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
$MyTamagochiRoster = Database::rawQueryWithReturnAll(sprintf("SELECT * FROM tamagotchis WHERE account_id = '%s' AND IS_ALIVE(tamagotchis.id) = 0", $_COOKIE["accountID"]));


include_once $_SERVER['DOCUMENT_ROOT'].'\templates\navbar.php'
?>
<?php foreach($MyTamagochiRoster as $tamagotchi): ?>
    <h1><?php echo $tamagotchi["name"]; ?></h1>
    <form action="TamagotchiEat.php" method="post">
        <input type="hidden" name="tamagotchiID" value="<?php echo $tamagotchi["id"]; ?>">
        <input type="submit" value="Nourrir">
    </form>
    <form action="TamagotchiPlay.php" method="post">
        <input type="hidden" name="tamagotchiID" value="<?php echo $tamagotchi["id"]; ?>">
        <input type="submit" value="Boire">
    </form>
    <form action="TamagotchiSleep.php" method="post">
        <input type="hidden" name="tamagotchiID" value="<?php echo $tamagotchi["id"]; ?>">
        <input type="submit" value="Dormir">
    </form>
    <form action="TamagotchiEnjoy.php" method="post">
        <input type="hidden" name="tamagotchiID" value="<?php echo $tamagotchi["id"]; ?>">
        <input type="submit" value="S'amuser">
    </form>

<?php endforeach; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous">
</script>
</body>
</html>