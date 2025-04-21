<?php
namespace FlowFinder\Api;

class Api
{
    function __construct()
    {
        
    }

    protected function getUrlParametersAsArry()
    {
        $url_parameters_string = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
        if(strlen($url_parameters_string) == 0)
            return array();
        
        $params = array();
        $parts = explode("&", $url_parameters_string);
        foreach ($parts as $part) {
            $key_value = explode("=", $part);
            $params[$key_value[0]] = null;
            if(count($key_value)>1)
            {
                $params[$key_value[0]] = urldecode($key_value[1]);
            }
        }

        return $params;

    }
}
