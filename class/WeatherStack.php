<?php
require_once 'ApiCurl.php';

class WeatherStack extends ApiCurl
{

    private $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function getData(string $city = 'Paris', string $units = 's'):array
    {
        if(!empty($this->apiKey))
        {
            $url = "http://api.weatherstack.com/current?access_key={$this->apiKey}&query={$city}&units={$units}";
            $curl = new ApiCurl($url);
            $api = $curl->curlInit();

            try
            {
                $data = $curl->exec($api);
            }
            catch (Exception $e)
            {
                die($e->getMessage());
            }
            finally
            {
                $curl->close($api);
            }

            if(array_key_exists("success", $data))
            {
                throw new Exception("ERROR CODE {$data['error']['code']} | TYPE : {$data['error']['type']} | INFO : {$data['error']['info']}");
            }

            return $data;
        }
        throw new Exception("UNDEFINED API KEY");
    }

    public function getForecast(string $city = 'Paris', string $units = 's'):array
    {
        $data = self::getData($city, $units);
        $results = [
            'city' => $data['request']['query'],
            'hour' => $data['location']['localtime'],
            'weather' => $data['current']['weather_descriptions'][0],
            'temp' => $data['current']['temperature'],
            'icon' => $data['current']['weather_icons'][0]
        ];

        return $results;
    }
}