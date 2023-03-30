<?php
declare(strict_types=1);
require_once 'class/WeatherStack.php';
require_once 'class/OpenWeather.php';

$error = null;

try
{
    // Insert your API key
    // $weatherStack = new WeatherStack('');
    // $results = $weatherStack->getForecast();
    // $results = $weatherStack->getData();

    // Insert your API key
    $openWeather = new OpenWeather('');
    $results = $openWeather->getForecast();
    $results = $weatherStack->getData();
}
catch(Exception | Error $e)
{
    $error = $e->getMessage();
}

require 'elements/header.php';
?>

<?php if($error): ?>
    <div class="alert alert-danger">
        <?= $error ?>
    </div>
<?php else: ?>
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
<?php endif; ?>

<?php
require 'elements/footer.php';
?>