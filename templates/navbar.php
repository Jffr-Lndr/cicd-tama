<?php

if( !isset($_COOKIE["accountName"]) ){
    setcookie("invalidCredential", 0,time()+5, "/");
    header("location: /");
}

?>

<nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Tamagotchi BDD</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                <a class="nav-link" aria-current="page" href="Home.php">Acceuil</a>
                </li>
                <li class="nav-item">
                <a class="nav-link " aria-current="page" href="TamagotchiRoster.php">Liste des Tamagotchi</a>
                </li>
                <li class="nav-item">
                <a class="nav-link " aria-current="page" href="TamagotchiActions.php">Action d'un Tamagotchi</a>
                </li>
                <li class="nav-item">
                <a class="nav-link " aria-current="page" href="DeathsTamagotchis.php">Cimetière des Tamagotchi</a>
                </li>
                <li class="nav-item">
                <a class="nav-link " aria-current="page" href="Disconnect.php">Se Déconnecter</a>
                </li>
            </ul>
        </div>
    </nav>