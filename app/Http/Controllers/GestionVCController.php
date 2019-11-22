<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GestionVCController extends Controller
{
    public function solicitudVC(){
      if (isset($_GET['id'])) {
    		$idTarea = $_GET['id'];
        $tarea = $this->obtenerTarea($idTarea);
    	} else {
    		$idTarea = "No se paso bien el id";
    	}
      $actorId = $tarea->{"actorId"};
      $caseId = $tarea->{"caseId"};
    	return view('solicitudVC', ['idTarea' => $idTarea, 'idActor' => $actorId, 'idCase' => $caseId]);
    }

    public function listaDeProcesos(){
        $response = GuzzleController::requestBonita('GET','API/bpm/process?p=0&c=1000');
        $info =$response->getBody();
        echo "<br/>";
        echo "Listado de procesos disponibles </br>";
        echo ($info);  // muestra mensaje de advertencia de php pero no es error
        echo "<br/>";
    }

    private function obtenerTarea($id){
      $uri = 'API/bpm/task/'.$id;
      $response = GuzzleController::requestBonita('GET',$uri);
      $tarea = json_decode($response->getBody());
      return $tarea;
    }

    public function enviarDatosSolicitud(){
      if (isset($_POST['id_tarea'])) {
    		$idTarea = $_POST['id_tarea'];
    	}

      $response = GuzzleController::requestBonita('POST','API/bpm/userTask/'.$idTarea.'/execution')

    }


}
