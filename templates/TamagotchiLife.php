<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <?php
    include_once $_SERVER['DOCUMENT_ROOT'].'\templates\navbar.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'\Database.php';
    Database::use('cicd_tama');

    $life = Database::rawQueryWithReturnOne("SELECT * FROM tamagotchi_life WHERE id = ".$_GET["id"]);
    $tamagotchiName = Database::rawQueryWithReturnOne("SELECT name FROM tamagotchis WHERE id = ".$_GET["id"])[0];
    $data = array(
        "id" => $life["id"],
        "nourriture" => $life["eat_count"],
        "boisson" => $life["drink_count"],
        "sommeil" => $life["bedtime_count"],
        "joie" => $life["enjoy_count"],
        "niveau" => $life["level"],
    );
    $headers = array_keys($data);
    $data_values = array_map('floatval', array_values($data));
    $data = array();
    $data[] = $headers;
    $data[] = $data_values;

    $json_data = json_encode($data);
    ?>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <script>
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable(<?php echo $json_data; ?>);
            var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
            chart.draw(data, {title: '<?php  echo $tamagotchiName; ?>', width: 600, height: 400});
        }
    </script>

</head>

<body>

<div id="chart_div"></div>

<div>
    <p>Date de naissance: <?php echo $life["birthdate"]; ?></p>
    <?php if($life["deathdate"] != null) : ?>
        <p>Date de mort: <?php echo $life["deathdate"]; ?></p>
    <?php endif ; ?>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>

</body>
</html>