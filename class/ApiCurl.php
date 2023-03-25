<?php
class ApiCurl
{

    private $url;

    public function __construct(string $url)
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

    public function curlInit():CurlHandle
    {
        $api = curl_init($this->url);
        curl_setopt_array($api, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 1
        ]);
        return $api;
    }

    public function exec(CurlHandle $api):array
    {
        if(!empty($api))
        {
            $data = curl_exec($api);

            if($data === false)
            {
                throw new Exception(curl_error($api));
            }
            
            if(curl_getinfo($api, CURLINFO_HTTP_CODE) !== 200)
            {
                throw new Exception($data);
            }

            return json_decode($data, true);
        }
        throw new Exception("CURL IS EMPTY");
    }

    public function close(CurlHandle $api):void
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