<?php


namespace Curl\Api;

use Exception;
use Curl\ {
    ApiCurl,
    Exceptions\HTTPException
};

/**
 * Permet d'utiliser l'API de Weather Stack à partir de la class ApiCurl
 * 
 * @author Svein Samson <samson.svein@gmail.com>
 */
class WeatherStack extends ApiCurl
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
     * @param  string $units Unités de mesure (ex: m)
     * 
     * @throws Exception si la clef API est vide
     * @throws Exception si l'exécution de cURL ne renvoie pas de donnée
     * @throws HTTPException si l'API renvoie succes : false
     * 
     * @return array
     */
    public function getData(string $city = 'Paris', string $units = 's'):array
    {
        if(!empty($this->apiKey))
        {
            $this->curl = new ApiCurl("http://api.weatherstack.com/current?access_key={$this->apiKey}&query={$city}&units={$units}");
            $api = $this->curl->curlInit();
            $data = $this->curl->exec($api);
            $this->curl->close($api);

            if(array_key_exists("success", $data))
            {
                throw new HTTPException("ERROR CODE {$data['error']['code']} | TYPE : {$data['error']['type']} | INFO : {$data['error']['info']}");
            }
            elseif(empty($data))
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