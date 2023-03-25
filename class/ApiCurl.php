<?php

class ApiCurl
{

    private $url;

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

    protected function curlInit():CurlHandle
    {
        $api = curl_init($this->url);
        curl_setopt_array($api, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 1
        ]);
        return $api;
    }

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
                    throw new Exception($data['message'], 401);
                }
                throw new Exception($data, $code);
            }

            return json_decode($data, true);
        }
        throw new Exception("CURL IS EMPTY");
    }

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