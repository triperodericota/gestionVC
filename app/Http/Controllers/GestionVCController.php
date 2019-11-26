<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DateTime, DateTimeZone;

class GestionVCController extends Controller
{

    private function getDB(){
      return DB::connection()->getPdo();
    }

    private function obtenerUsuarios(){
        /*
        traigo los roles con /API/identity/role?p=0&c=100&o=displayName ASC
        */
        $response = GuzzleController::requestBonita('GET','API/identity/user?p=0&c=10');
        $usuarios = json_decode($response->getBody());
        return $usuarios;
    }

    private function datosFormularioSolicitud($tarea){
      $idTarea = $tarea->{"id"};
      $actorId = $tarea->{"actorId"};
      $caseId = $tarea->{"caseId"};
      $userId = $tarea->{"assigned_id"};

      /* sql para buscar unidades */
      $db = $this->getDB();
      $sql = $db->prepare('select id,nombre from unidades');
      $sql->execute();
      $unidades = $sql->fetchAll();

      /* obtener posibles Participantes
        $response = GuzzleController::requestBonita('GET', 'API/bpm/actorMember?p=0&c=10&f=actor\_id%3d1&f=member\_type%3duser')
      */
      $participantes=array();
      $users = $this->obtenerUsuarios();
      foreach ($users as $user) {
        if(($user->{'job_title'} == 'Procurador') || ($user->{'job_title'} == 'Juez') || ($user->{'job_title'} == 'Abogado')) {
          $users[$user->{'id'}]=$user;
        }
      }
      return ['idTarea' => $idTarea, 'idActor' => $actorId, 'idCase' => $caseId, 'idUser' => $userId, 'unidades' => $unidades, 'participantes' => $users];
    }

    public function solicitudVC(){
      if (isset($_GET['id'])) {
    		$idTarea = $_GET['id'];
        $tarea = $this->obtenerTarea($idTarea);
        $datosForm = $this->datosFormularioSolicitud($tarea);
    	} else {
    		$idTarea = "No se paso bien el id";
    	}

    	return view('solicitudVC', $datosForm);
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
      return $tarea;

    }

    public function enviarDatosSolicitudVC(Request $request){
      $participantes=[ "id_participante1" => $request->participante1, "id_participante2" => $request->participante2, "id_participante3" => $request->participante3];
      /*$participantes = array_map('strval',$participantes);*/

      /* recupero informacion del usuario */
      $response = GuzzleController::requestBonita('GET','API/identity/user/'.$request->id_solicitante.'?d=professional_data');
      $solicitante = json_decode($response->getBody());
      //determino si es videoconferencia o comparendo
      if($solicitante->{'job_title'} == 'Juez'){
        $tipo_vc = "Comparendo";
      }else{
        $tipo_vc = "Entrevista";
      }

      $fecha = date('Y-m-d', strtotime($request->fecha)); /* sin timezone */
      /*$fecha = $fecha->format('Y-m-d H:i:s');
      $fecha = new DateTime($request->fecha, new DateTimeZone('UTC'));
      echo($fecha->format('Y-m-d')); // tampoco mapea
      */
      echo(var_dump($request));
      $contrato = ['videoconferenciaInput' => [
          'id_solicitante' => intval($request->id_solicitante),
          'id_interno' => intval($request->id_interno),
          'id_unidad' => intval($request->id_unidad),
          'fecha' => $fecha,
          'hora' => $request->hora,
          'nro_causa' => intval($request->nro_causa),
          'motivo' => $request->motivo,
          'tipo_vc' => $tipo_vc,
          'participantes' => $participantes
          ]];

      $response = GuzzleController::requestBonita('POST','API/bpm/userTask/'.$request->id_tarea.'/execution',$contrato);
      echo(var_dump($response));

      $response = GuzzleController::requestBonita('PUT','API/bpm/userTask/'.$request->id_tarea,['state' => 'completed']);
      echo("chenge state ===>".json_decode($response));
    }

    public function posiblesAlternativas()
    {
      if(isset($_GET['id'])){
        $idTarea = $_GET['id'];
        $tarea = $this->obtenerTarea($idTarea);
        $datosForm = $this->datosFormularioSolicitud($tarea);
    	} else {
    		$idTarea = "No se paso bien el id";
    	}

      $caseId = $datosForm['caseId'];
      /* obtengo las posibles alternativas */
      $response = GuzzleController::requestBonita('GET','API/bpm/caseVariable/'.$caseId.'/alternativas');
      echo(json_decode($response));



    }


}
