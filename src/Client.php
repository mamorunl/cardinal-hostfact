<?php

namespace Tnpdigital\Cardinal\Hostfact;

class Client
{
    public static function sendRequest($controller, $action, $params)
    {
        if(!is_array($params)) {
            throw new \Exception('Parameters not set');
        }

        if(config('hostfact.api_key', 'NOT_SET') == 'NOT_SET') {
            throw new \Exception('HostFact API key not set');
        }

        if(config('hostfact.api_url', 'NOT_SET') == 'NOT_SET') {
            throw new \Exception('HostFact API URL not set');
        }

        $params['api_key'] = config('hostfact.api_key', 'NOT_DEFINED');
        $params['controller'] = $controller;
        $params['action'] = $action;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, config('hostfact.api_url'));
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, '10');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        $curlResp = curl_exec($ch);
        $curlError = curl_error($ch);

        if ($curlError != '') {
            throw new \Exception('Result canceled due to Curl Error: ' . $curlError);
        } else {
            $result = json_decode($curlResp, true);

            if(!strcasecmp($result['status'], 'error') && !strcasecmp($result['errors'][0], 'API key is invalid')) {
                throw new \Exception($result['errors'][0]);
            }

            if(strcasecmp($result['status'], 'success')) {
                throw new \Exception($result['errors'][0]);
            }
        }

        return $result;
    }
}