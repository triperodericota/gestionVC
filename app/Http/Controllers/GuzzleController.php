<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\SessionCookieJar;

class GuzzleController extends Controller
{
#    private static $user = 'root';
#    private static $password = 'root';
#    private static $base_uri = 'http://localhost:8080/bonita/';

    private static $cliente = null;
    private static $token = null;

	public static function getGuzzleClient(){
           $base_uri = env('BONITA_URI', 'http://localhost:8080/bonita/'); 
           $user = env('BONITA_USER', 'root');
           $password = env('BONITA_PASSWORD', 'root');
        if(static::$cliente === null){
        	//Creo una cookie jar para almacenar las cookies que me va a devolver Bonita luego del request del loginservice
            $cookieJar = new SessionCookieJar('MiCookie', true);
            $gcliente = new Client([
                // Base URI is used with relative requests
                'base_uri' => $base_uri,
                // Timeout en segundos
                'timeout'  => 4.0,
                'cookies' => $cookieJar
            ]);

            $resp = $gcliente->request('POST', 'loginservice', [
                'form_params' => [
                    'username' => $user,
                    'password' => $password,
                    'redirect' => 'false'
                ]
            ]);

        	$token = $cookieJar->getCookieByName('X-Bonita-API-Token');
        	static::$token = $token->getValue();

        	static::$cliente = $gcliente;
        }

        return static::$cliente;
    }

    public static function getToken(){
    	return static::$token;
    }


    public static function requestBonita($method, $uri, $data=[]){
        $response = array();
		    try {
            $client = static::getGuzzleClient();
            $response = $client->request($method, $uri,
                    [
                    	'headers' => [
                        	'X-Bonita-API-Token' => static::getToken()
                        ],
                    	'json' => $data
                    ]);
            return $response->getBody();
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $error = Psr7\str($e->getResponse());
            } else {
                $error = "No se puede conectar al servidor de Bonita OS";
            }

            return $error;
        }
    }

    public static function setCaseVariable($idCase,$variableName,$value){
        return GuzzleController::requestBonita('PUT', "API/bpm/caseVariable/".$idCase."/".$variableName,$value);
    }
}
