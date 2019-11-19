<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GestionVCController extends Controller
{
    public function solicitudVC(){
      if (isset($_GET['id'])) {
    		$idTarea = $_GET['id'];
        /*$tarea = $this->obtenerTarea($id);*/
    	} else {
    		$idTarea = "No se paso bien el id";
    	}
    	return view('solicitudVC', ['idTarea' => $idTarea]);
    }

    private function obtenerTarea($id){
      try {
        $method = 'GET';
        $uri = '/API/bpm/task/'.$id;
            $client = GuzzleController::getGuzzleClient();
            $request = $client->request($method, $uri,
                    [
                      'headers' => [
                          'X-Bonita-API-Token' => GuzzleController::getToken()
                        ],
                    ]);

        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $error = Psr7\str($e->getResponse());
            } else {
                $error = "No se puede conectar al servidor de Bonita OS";
            }

            return $error;
        }
        var_dump($request);
        return $tarea;
    }


}
