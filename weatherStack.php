<?php
require_once 'class/WeatherStack.php';

$error = null;

if(!empty($_POST))
{
    $city = htmlentities($_POST ['city']);
    $unit = htmlentities($_POST['unit']);
    $apiKey = htmlentities($_POST['apiKey']);

    try
    {
    // Insert your API key
    $meteo = new WeatherStack($apiKey);  
    $results = $meteo->getForecast($city, $unit);
    }
    catch (Exception $e)
    {
        $error = $e->getMessage();
    }
}

require 'elements/header.php';
?>

<?php if($error): ?>
    <div class="alert alert-danger">
        <?= $error ?>
    </div>
<?php elseif(!empty($_POST)): ?>
    <div class="container">
        <h1>WeatherStack</h1>
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

<div class="container">
    <h1>WeatherStack</h1>

    <form method="POST">
        <div class="form-group">
            <label for="city">City</label>
            <input type="text" name="city" id="city" placeholder="Enter a city">
        </div>

        <div class="form-group">
            <label for="apiKey">Api key</label>
            <input type="text" name="apiKey" id="apiKey" placeholder="Enter your API key">
        </div>

        <div class="form-group">
            <label for="metric">Metric</label>
            <input type="radio" id="metric" name="unit" value="m">
        </div>

        <div class="form-group">
            <label for="scientific">Scientific</label>
            <input type="radio" id="scientific" name="unit" value="s">
        </div>

        <div class="from-group">
            <label for="fahrenheit">Fahrenheit</label>
            <input type="radio" id="fahrenheit" name="unit" value="f">
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

<?php
require 'elements/footer.php';
?>