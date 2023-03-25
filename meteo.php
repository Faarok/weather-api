<?php
require_once 'class/WeatherStack.php';

// Insert your API key
try
{
    $meteo = new WeatherStack('');
    $results = $meteo->getForecast("Bordeaux", "m");
    // $results = $meteo->getData("Bordeaux", "m");
}
catch (Exception $e)
{
    die($e->getMessage());
}

require 'elements/header.php';
?>

<div class="container">
    <h1>Météo</h1>
    <ul>
        <?php foreach($results as $item): ?>
            <li>
                <?php if(filter_var($item, FILTER_VALIDATE_URL)): ?>
                    <img src="<?= $item ?>">
                <?php else: ?>
                    <?= $item ?>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<?php
require 'elements/footer.php';
?>