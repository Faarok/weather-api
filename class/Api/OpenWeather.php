<?php

namespace Curl\Api;

use Exception;
use Curl\ApiCurl;

/**
 * Permet d'utiliser l'API d'Open Weather Map à partir de la class ApiCurl
 * 
 * @author Svein Samson <samson.svein@gmail.com>
 */
class OpenWeather extends ApiCurl
{

    private $apiKey;
    private $curl;
    
    /**
     * Instancie la class et initialise la clef API
     *
     * @param  string $apiKey
     * 
     * @throws Exception si la clef API est vide
     * 
     * @return void
     */
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
    
    /**
     * Récupère toutes les informations météorologique actuelle
     *
     * @param  string $city Ville (ex: Bordeaux)
     * @param  string $units Unités de mesure (ex: metric)
     * @param  string $lang Langue (ex: fr)
     * 
     * @throws Exception si la clef API est vide
     * @throws Exception si l'exécution de cURL ne renvoie pas de donnée
     * 
     * @return array
     */
    public function getData(string $city = 'Paris', string $units = 'standard', string $lang = 'en'):array
    {
        if(!empty($this->apiKey))
        {
            $this->curl = new ApiCurl("https://api.openweathermap.org/data/2.5/weather?&q={$city}&units={$units}&lang={$lang}&appid={$this->apiKey}");
            $api = $this->curl->curlInit();
            $data = $this->curl->exec($api);
            $this->curl->close($api);

            if(empty($data))
            {
                throw new Exception("THERE IS NO RESULT");
            }

            return $data;
        }
        throw new Exception("UNDEFINED API KEY");
    }
    
    /**
     * Récupère la météo actuelle (ville, heure, temps, température et une icone)
     *
     * @param  string $city Ville (ex: Bordeaux)
     * @param  string $units Unités de mesure (ex: metric)
     * @param  string $lang Langue (ex: fr)
     * 
     * @return array
     */
    public function getForecast(string $city = 'Paris', string $units = 'standard', string $lang = 'en'):array
    {
        $data = self::getData($city, $units, $lang);
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