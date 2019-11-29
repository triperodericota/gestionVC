<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
        $usuarios = json_decode($response);
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
        $info =$response;
        echo "<br/>";
        echo "Listado de procesos disponibles </br>";
        echo ($info);  // muestra mensaje de advertencia de php pero no es error
        echo "<br/>";
    }

    private function obtenerTarea($id){
      $uri = 'API/bpm/task/'.$id;
      $response = GuzzleController::requestBonita('GET',$uri);
      $tarea = json_decode($response);
      return $tarea;

    }

    public function enviarDatosSolicitudVC(Request $request){
      /*$participantes=[ "id_participante1" => $request->participante1, "id_participante2" => $request->participante2, "id_participante3" => $request->participante3];
      /*$participantes = array_map('strval',$participantes);*/
      /* recupero informacion del usuario */
      echo($request->id_solicitante);
      if($request->id_solicitante == null){
        $response = GuzzleController::requestBonita('GET','API/bpm/case/'.$request->id_case);
        $id_solicitante = $response->{"started_by"};
      }else{
        $id_solicitante = $request->id_solicitante;
      }

      $response = GuzzleController::requestBonita('GET','API/identity/user/'.$id_solicitante.'?d=professional_data');
      $solicitante = json_decode($response);
      //determino si es videoconferencia o comparendo
      if((isset($solicitante->{'job_title'})) && ($solicitante->{'job_title'} == 'Juez')){
        $tipo_vc = "Comparendo";
      }else{
        $tipo_vc = "Entrevista";
      }

      $fecha = date('d/m/Y', strtotime($request->fecha)); /* sin timezone */
      /*$fecha = $fecha->format('Y-m-d H:i:s');
      $fecha = new DateTime($request->fecha, new DateTimeZone('UTC'));
      echo($fecha->format('Y-m-d')); // tampoco mapea
*/
      echo(var_dump($request));
/*      $contrato = ['videoconferenciaInput' => [
          'id_solicitante' => intval($request->id_solicitante),
          'id_interno' => intval($request->id_interno),
          'id_unidad' => intval($request->id_unidad),
          'fecha' => $fecha,
          'hora' => $request->hora,
          'nro_causa' => intval($request->nro_causa),
          'motivo' => $request->motivo,
          'tipo_vc' => $tipo_vc,
          'id_participante1' => $request->participante1,
          'id_participante2' => $request->participante2,
          'id_participante3' => $request->participante3,
          ]];*/

      $query_api_disp = '{"date":'.$fecha.',"time":'.$request->hora.',"id_unidad":'.$request->id_unidad."}";
      echo("QUERY = ".$query_api_disp);

      // set variables
      GuzzleController::setCaseVariable($request->id_case,'fecha',['type' => "java.lang.String", "value" => $fecha]);
      GuzzleController::setCaseVariable($request->id_case,"hora",['type' => "java.lang.String", "value" => $request->hora]);
      GuzzleController::setCaseVariable($request->id_case,"id_interno",['type' => "java.lang.String", "value" => $request->id_interno]);
      GuzzleController::setCaseVariable($request->id_case,"id_participante1",['type' => "java.lang.String", "value" => $request->participante1]);
      GuzzleController::setCaseVariable($request->id_case,"id_participante2",['type' => "java.lang.String", "value" => $request->participante2]);
      GuzzleController::setCaseVariable($request->id_case,"id_participante3",['type' => "java.lang.String", "value" => $request->participante3]);
      GuzzleController::setCaseVariable($request->id_case,"id_solicitante",['type' => "java.lang.String", "value" => $id_solicitante]);
      GuzzleController::setCaseVariable($request->id_case,"motivo",['type' => "java.lang.String", "value" => $request->motivo]);
      GuzzleController::setCaseVariable($request->id_case,"id_unidad",['type' => "java.lang.String", "value" => $request->id_unidad]);
      GuzzleController::setCaseVariable($request->id_case,"nro_causa",['type' => "java.lang.String", "value" => $request->nro_causa]);
      GuzzleController::setCaseVariable($request->id_case,"tipo_vc",['type' => "java.lang.String", "value" => $tipo_vc]);
      GuzzleController::setCaseVariable($request->id_case,"query_api_disp",['type' => "java.lang.String", "value" => $query_api_disp]);
/*
      $response = GuzzleController::requestBonita('POST','API/bpm/userTask/'.$request->id_tarea.'/execution',[]);
      echo(var_dump($response));*/

      $response = GuzzleController::requestBonita('PUT','API/bpm/task/'.$request->id_tarea,['state' => 'completed']);

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
    /*  $response = GuzzleController::requestBonita('GET','API/bpm/caseVariable/'.$caseId.'/alternativas');
      echo(json_decode($response));
      $datosForm["alternativas"] = $response;*/
      return view('solicitudVC', $datosForm);

    }

    private function getVariableValue($caseId,$varName){
      $variableJson = GuzzleController::requestBonita('API/bpm/caseVariable/'.$caseId."/".$varName);;
      return $variableJson->{"value"};
    }

    public function registrarSolicitudVC(){
      // carga en la base de datos la nueva solicitud de videoconferencias
      //echo(var_dump($_POST));
      if(isset($_GET["activityId"])){
        $activityId = $_GET["activityId"];
        $response = GuzzleController::requestBonita('GET','API/bpm/activity/'.$activityId);
        $caseId = $response->{"caseId"};

        $fecha = $this->getVariableValue($caseId,'fecha');
        $hora = $this->getVariableValue($caseId,'hora');
        $id_solicitante = $this->getVariableValue($caseId,'id_solicitante');
        $id_unidad = $this->getVariableValue($caseId,'id_unidad');
        $id_interno = $this->getVariableValue($caseId,'id_unidad');
        $nro_causa = $this->getVariableValue($caseId,'nro_causa');
        $tipo_vc = $this->getVariableValue($caseId,'motivo');
        $id_participante1 = $this->getVariableValue($caseId,'id_participante1');
        $id_participante2 = $this->getVariableValue($caseId,'id_participante2');
        $id_participante3 = $this->getVariableValue($caseId,'id_participante3');

        $tipo_participante =

      }



      //return view('registrarSolicitudVC');
    }


}
