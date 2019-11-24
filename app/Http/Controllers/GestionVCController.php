<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DateTime;

class GestionVCController extends Controller
{

    private function getDB(){
      return DB::connection()->getPdo();
    }

    public function solicitudVC(){
      if (isset($_GET['id'])) {
    		$idTarea = $_GET['id'];
        $tarea = $this->obtenerTarea($idTarea);
    	} else {
    		$idTarea = "No se paso bien el id";
    	}
      $actorId = $tarea->{"actorId"};
      $caseId = $tarea->{"caseId"};
      $userId = $tarea->{"executedBy"};

      /* sql para buscar unidades */
      $db = $this->getDB();
      $sql = $db->prepare('select id,nombre from unidades');
      $sql->execute();
      $unidades = $sql->fetchAll();

      /* obtener posibles Participantes
        $response = GuzzleController::requestBonita('GET', 'API/bpm/actorMember?p=0&c=10&f=actor\_id%3d1&f=member\_type%3duser')
      */
    	return view('solicitudVC', ['idTarea' => $idTarea, 'idActor' => $actorId, 'idCase' => $caseId, 'idUser' => $userId, 'unidades' => $unidades]);
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
      $uri = 'API/bpm/userTask/'.$id;
      $response = GuzzleController::requestBonita('GET',$uri);
      $tarea = json_decode($response->getBody());
      /* PREGUNTAR: siempre tira executedBy=0 siendo una tarea humnana */
      return $tarea;

    }

    public function enviarDatosSolicitudVC(Request $request){
      /* sql para buscar Participantes segun el nombre  */

      /* segun el id_solicitante determinar tipo de tarea */
      /* recupero informacion del usuario
        $response = GuzzleController::requestBonita('GET','/API/identity/professionalcontactdata/'.$request->>id_solicitante);
        //determino si es videoconferencia o comparendo

       */
      $fecha = new DateTime($request->fecha." ".$request->hora);
      $fecha = $fecha->format('Y-m-d H:i:sP');
      $contrato = ['videoconferenciaInput' => [
          'id_solicitante' => $request->id_solicitante,
          'id_interno' => $request->id_interno,
          'id_unidad' => $request->id_unidad,
          'fecha' => $fecha,
          'nro_causa' => $request->nro_causa,
          'motivo' => $request->motivo
          ]];

      $response = GuzzleController::requestBonita('POST','API/bpm/userTask/'.$request->id_tarea.'/execution',$contrato);
      echo(var_dump($response));
/*
      $response = GuzzleController::requestBonita('PUT','API/bpm/userTask/'.$request->id_tarea,['state' => 'skipped']);
      echo(var_dump($response));*/
    }


}
