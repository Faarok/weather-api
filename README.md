# weather-api 

PHP work I made while doing technological watch with Grafikart exercices.
I decided to add another API than the one that Grafikart use, to train my documentation reading (he use OpenWeatherMap, and I added Weather Stack).

Because it wasn't enough for me, I decided to create an another class, very light, which allows someone to initalize cURL quickly and easily for another API.
The Weather Stack class is actually made as a **proof of concept**.

***

## How to use it ?

1. Create your class. It has to extend from ApiCurl (Include it the way you want).
The ApiCurl constructor add the URL of your API in parameter.

```php
<?php
class myNewApi extends ApiCurl
{
    private $curl;

    public function yourMethod()
    {
        $this->curl = new ApiCurl('Your URL');
    }
}
?>
```

2. Call the intialization method from ApiCurl : `curlInit()`. It'll return a `CurlHandle` value. The options used in `curl_setopt()` are simple :
    * Timeout after 1s without answer,
    * Return a string value instead of showing it.

```php
$api = $this->curl->curlInit();
```

3. Execute cURL, through the method : `exec()`. It require a CurlHandle variable in parameter. Use the one you received from `curlInit()`. It will return an `array` (the method already decode the JSON).

```php
$data = $this->curl->exec($api);
```

4. If needed, the method `close()` allows you to end the session of cURL. It take the `CurlHandle` variable in parameter (like `$api` from the previous example).
```php
$this->curl->close($api);
```
<br>

***This is how your final result should look like :***

```php
<?php
class myNewApi extends ApiCurl
{
    private $curl;

    public function yourMethod()
    {
        try
        {
            $this->curl = new ApiCurl('Your URL');
            $api = $this->curl->curlInit();
            $data = $this->curl->exec($api);
            // Then do whatever you want with the returned array.

            $this->curl->close($api); 

            return $data;
        }
        catch (Exception | Error $e)
        {
            $this->curl->close($api);
            $e->getMessage();
        }
    }
}
?>
```
**You can obviously do it differently !**

***

As you can see. It's pretty simple. <br>
I'll maybe upgrade it in the future if I have to use another API.