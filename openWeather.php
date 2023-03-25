<?php
require_once 'class/OpenWeather.php';

$error = null;

if(!empty($_POST))
{
    $city = htmlentities($_POST ['city']);
    $unit = htmlentities($_POST['unit']);
    $apiKey = htmlentities($_POST['apiKey']);

    try
    {
    // Insert your API key
    $meteo = new OpenWeather($apiKey);  
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
        <h1>OpenWeather</h1>
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
    <h1>OpenWeather</h1>

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
            <input type="radio" id="metric" name="unit" value="metric">
        </div>

        <div class="form-group">
            <label for="standard">Standard</label>
            <input type="radio" id="standard" name="unit" value="standard">
        </div>

        <div class="from-group">
            <label for="imperial">Imperial</label>
            <input type="radio" id="imperial" name="unit" value="imperial">
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

<?php
require 'elements/footer.php';
?>