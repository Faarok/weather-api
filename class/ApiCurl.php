<?php

require_once 'UnauthorizedHTTPException.php';
require_once 'HTTPException.php';

/**
 * Class légère permettant d'initialiser libcurl.
 * 
 * @author Svein Samson <samson.svein@gmail.com>
 */
class ApiCurl
{

    private $url;
    
    /**
     * Donne la valeur à la variable $url lors de l'instanciation de la classe
     *
     * @param  string $url
     * 
     * @throws Exception L'URL définie est vide
     * 
     * @return void
     */
    protected function __construct(string $url)
    {
        if(!empty($url))
        {
            $this->url = $url;
        }
        else
        {
            throw new Exception("UNDEFINED URL");
        }
    }
    
    /**
     * Initialise libcurl à partir de l'URL donnée dans le constructeur
     * 
     * @return CurlHandle
     */
    protected function curlInit():CurlHandle
    {
        $api = curl_init($this->url);
        curl_setopt_array($api, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 1
        ]);
        return $api;
    }
    
    /**
     * Exécute libcurl à partir de la variable $api récupérer lors de l'initialisation de libcurl.
     *
     * @param  CurlHandle $api
     * 
     * @throws Exception L'API en paramètre est vide
     * @throws Exception L'exécution a renvoyé false
     * @throws UnauthorizedHTTPException L'exécution a renvoyé une erreur HTTP 401
     * @throws HTTPException L'exécution a renvoyé une erreur HTTP 
     * 
     * @return array
     */
    protected function exec(CurlHandle $api):array
    {
        if(!empty($api))
        {
            $data = curl_exec($api);

            if($data === false)
            {
                throw new Exception(curl_error($api));
                self::close($api);
            }
            
            $code = curl_getinfo($api, CURLINFO_HTTP_CODE);
            if($code !== 200)
            {
                curl_close($api);
                if($code === 401)
                {
                    $data = json_decode($data, true);
                    throw new UnauthorizedHTTPException($data['message'], 401);
                }
                throw new HTTPException($data, $code);
            }

            return json_decode($data, true);
        }
        throw new Exception("CURL IS EMPTY");
    }
    
    /**
     * Ferme la session de cURL
     *
     * @param  CurlHandle $api
     * 
     * @throws Exception L'API en paramètre est vide
     * 
     * @return void
     */
    protected function close(CurlHandle $api):void
    {
        if(!empty($api))
        {
            curl_close($api);
        }
        else
        {
            throw new Exception("CURL IS EMPTY");
        }
    }    
}
?>