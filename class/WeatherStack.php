<?php
require_once 'ApiCurl.php';

class WeatherStack extends ApiCurl
{

    private $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function getForecast(string $city = 'Paris', string $units = 's'):array
    {
        if(!empty($this->apiKey))
        {
            $url = "http://api.weatherstack.com/current?access_key={$this->apiKey}&query={$city}&units={$units}";
            $curl = new ApiCurl($url);

            $api = $curl->curlInit();
            $data = $curl->exec($api);
            $curl->close($api);

            $results = [
                'city' => $data['request']['query'],
                'hour' => $data['location']['localtime'],
                'weather' => $data['current']['weather_descriptions'][0],
                'temp' => $data['current']['temperature'],
                'icon' => $data['current']['weather_icons'][0]
            ];

            return $results;
        }
        throw new Exception("API KEY NOT GIVEN");
    }
}