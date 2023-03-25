<?php
require_once 'ApiCurl.php';

class OpenWeather extends ApiCurl
{

    private $apiKey;

    public function __construct(string $apiKey)
    {
        if(!empty($apiKey))
        {
            $this->apiKey = $apiKey;
        }
        else
        {
            throw new Exception("UNDEFINED API KEY");
        }
    }

    public function getData(string $city = 'Paris', string $units = 'standard', string $lang = 'en'):array
    {
        if(!empty($this->apiKey))
        {
            $url = "https://api.openweathermap.org/data/2.5/weather?&q={$city}&units={$units}&lang={$lang}&appid={$this->apiKey}";
            $curl = new ApiCurl($url);
            $api = $curl->curlInit();
            $data = $curl->exec($api);
            $curl->close($api);

            if(empty($data))
            {
                throw new Exception("THERE IS NO RESULT");
            }

            return $data;
        }
        throw new Exception("UNDEFINED API KEY");
    }

    public function getForecast(string $city = 'Paris', string $units = 'standard', string $lang = 'en'):array
    {
        $data = self::getData($city, $units);
        $results = [
            'city' => $data['name'],
            'hour' => date('d/m/Y à H:i', $data['dt']),
            'weather' => ucfirst($data['weather'][0]['description']),
            'temp' => "{$data['main']['temp']}°C",
            'icon' => "https://openweathermap.org/img/wn/{$data['weather'][0]['icon']}@2x.png"
        ];

        return $results;
    }
}