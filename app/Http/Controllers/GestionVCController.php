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

    private function excecuteSQL($query){
      $db = $this->getDB();
      $sql = $db->prepare($query);
      $sql->execute();
      return $sql->fetchAll();
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
      $vc = $this->getVariableValue($caseId,'id_videoconferencia');
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
      return ['idTarea' => $idTarea, 'idActor' => $actorId, 'idCase' => $caseId, 'idUser' => $userId, 'unidades' => $unidades, 'participantes' => $users, 'idVC' =>$vc];
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

    private function obtenerTarea($id){
      $uri = 'API/bpm/task/'.$id;
      $response = GuzzleController::requestBonita('GET',$uri);
      $tarea = json_decode($response);
      return $tarea;

    }

    public function enviarDatosSolicitudVC(Request $request){
      if($request->id_solicitante == null){
        $response = GuzzleController::requestBonita('GET','API/bpm/case/'.$request->id_case);
        $id_solicitante = json_decode($response)->started_by;
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
      $fecha = date('d/m/Y', strtotime($request->fecha));

      #echo(var_dump($request));
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

      /*$query_api_disp = array("date" => '"'.$fecha.'"', "time" => '"'.$request->hora.'"' ,"idUnidad" => '"'.$request->id_unidad.'"');
      echo("QUERY = ".json_encode($query_api_disp));*/

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
      /*GuzzleController::setCaseVariable($request->id_case,"query_api_disp",['type' => "java.lang.String", "value" => $query_api_disp]);
*/
      #$response = GuzzleController::requestBonita('POST','API/bpm/userTask/'.$request->id_tarea.'/execution',[]);
      #echo(var_dump($response));

      $response = GuzzleController::requestBonita('PUT','API/bpm/task/'.$request->id_tarea,['state' => 'completed']);
      //echo(var_dump($response));
    }

    public function posiblesAlternativas()
    {
      if(isset($_GET['id'])){
        $idTarea = $_GET['id'];
        $tarea = $this->obtenerTarea($idTarea);
        $datosForm = $this->datosFormularioSolicitud($tarea);

        $caseId = $datosForm['idCase'];
        /* obtengo las posibles alternativas */
        $datosForm["alternativas"] = $this->getVariableValue($caseId,'api_alternativas');
        /*echo($datosForm["alternativas"]);*/
        $datosForm["id_unidad"] = $this->getVariableValue($caseId,'id_unidad');
        $datosForm["id_interno"] = $this->getVariableValue($caseId,'id_interno');
        $datosForm["motivo"] = $this->getVariableValue($caseId,'motivo');
        $datosForm["fecha"] = $this->getVariableValue($caseId,'fecha');
        $datosForm["hora"] = $this->getVariableValue($caseId,'hora');
        $datosForm["nro_casua"] = $this->getVariableValue($caseId,'nro_causa');
        $datosForm["id_participante1"] = $this->getVariableValue($caseId,'id_participante1');
        $datosForm["id_participante2"] = $this->getVariableValue($caseId,'id_participante2');
        $datosForm["id_participante3"] = $this->getVariableValue($caseId,'id_participante3');
    	} else {
    		  $idTarea = "No se paso bien el id";
    	}

      return view('solicitudConAlternativas', $datosForm);

    }

    private function getVariableValue($caseId,$varName){
      $variableJson = GuzzleController::requestBonita('GET','API/bpm/caseVariable/'.$caseId.'/'.$varName);;
      return json_decode($variableJson)->value;
    }

    private function registrar_participante($idUser){
      $data_participante = GuzzleController::requestBonita('GET','API/identity/user/'.$idUser.'?d=professional_data');
      $email = json_decode($data_participante)->professional_data->email;
      $title_participante = json_decode($data_participante)->job_title;

      switch ($title_participante) {
        case 'Abogado':
          $id_tipo = 2;
          break;
        case 'Juez':
          $id_tipo = 3;
          break;
        case 'Procurador':
          $id_tipo = 4;
          break;
      }

      $existe = $this->excecuteSQL("SELECT id,tipo_participante FROM participante_videoconferencia WHERE email='".$email."'");
      if(empty($existe)){
        $nombres = json_decode($data_participante)->firstname;
        $apellido = json_decode($data_participante)->lastname;
        return DB::table('participante_videoconferencia')->insertGetId(
            ['tipo_participante' => $id_tipo, 'apellido' => $apellido, 'nombres' => $nombres, 'email' => $email]
          );
        }else{
        return $existe[0]["id"];
      }
    }

    public function registrarSolicitudVC(){
      // carga en la base de datos la nueva solicitud de videoconferencias
      if(isset($_GET["activityId"])){
        $activityId = $_GET["activityId"];
        $response = GuzzleController::requestBonita('GET','API/bpm/activity/'.$activityId);
	echo $response;
        $caseId = json_decode($response)->caseId;

        $fecha = $this->getVariableValue($caseId,'fecha');
        $fecha = date('Y-m-d', strtotime($fecha));
        echo("FECHA: $fecha");
        $hora = $this->getVariableValue($caseId,'hora');
        $hora = date("H:i", strtotime($hora));
        echo("HORA: $hora");
        $id_solicitante = $this->getVariableValue($caseId,'id_solicitante');
        $id_unidad = intval($this->getVariableValue($caseId,'id_unidad'));
        $id_interno = intval($this->getVariableValue($caseId,'id_interno'));
        $nro_causa = $this->getVariableValue($caseId,'nro_causa');
        $tipo_vc = $this->getVariableValue($caseId,'tipo_vc');
        $motivo = $this->getVariableValue($caseId,'motivo');
        $id_participante1 = $this->getVariableValue($caseId,'id_participante1');
        $id_participante2 = $this->getVariableValue($caseId,'id_participante2');
        $id_participante3 = $this->getVariableValue($caseId,'id_participante3');

        $id_db_solicitante = $this->registrar_participante($id_solicitante);
        switch ($tipo_vc) {
          case 'Entrevista':
            $id_tipo_vc = 2;
            break;
          case 'Comparendo':
            $id_tipo_vc = 1;
        }

        $id_videoconferencia = DB::table('videoconferencias')->insertGetId(
            ['fecha' => $fecha, 'hora' => $hora, 'unidad' => $id_unidad, 'estado' => 3, 'tipo' => $id_tipo_vc, 'nro_causa' => $nro_causa,
            'motivo' => $motivo, 'solicitante' => $id_db_solicitante]);
        GuzzleController::setCaseVariable($caseId,'id_videoconferencia',['type' => "java.lang.Integer", "value" => $id_videoconferencia]);
      }
      #return json_encode("ok");
      //return view('registrarSolicitudVC');
    }

    public function inicioVC(){
      if (isset($_GET['id'])) {
    		$idTarea = $_GET['id'];
        $tarea = $this->obtenerTarea($idTarea);
        #echo(var_dump($tarea));
        $datosForm = $this->datosFormularioSolicitud($tarea);
#        $case = $tarea->{"caseId"};
#      	$vc = $this->getVariableValue($case,'id_videoconferencia');
      } else {
    		$idTarea = "No se paso bien el id";
	    }
    	return view('inicioVC', $datosForm);
    }

    public function enviarInicioVC(Request $request){
/*       if (isset($_GET['id_vc'])) {
    		$id_vc = $_GET['id_vc'];
      #if($request->id_solicitante == null){
        #$response = GuzzleController::requestBonita('GET','API/bpm/case/'.$request->id_case);
        #$id_solicitante = json_decode($response)->started_by;
      #}else{
        $id_solicitante = $request->id_solicitante;
      }
		print_r($request->all());*/


      $fecha = date('Y-m-d');
      $hora = date("h:i:s");
      $id_vc = $request->id_vc;
      $tarea =  $request->id_tarea;
      $id_estado= $request->estadoInicio;
      $comentarios = $request->ComentariosInicio;

      DB::table('registro_videoconferencia')->insertGetId(
            ['fecha' => $fecha, 'hora' => $hora, 'estado' => $id_estado, 'descripcion' => $comentarios , 'videoconferencia' => $id_vc]);

      $response = GuzzleController::requestBonita('PUT','API/bpm/task/'.$request->id_tarea,['state' => 'completed']);
      #:wecho(var_dump($response));
    }

    public function finalizarVC(){
      if (isset($_GET['id'])) {
    		$idTarea = $_GET['id'];
        $tarea = $this->obtenerTarea($idTarea);
        #echo(var_dump($tarea));
        $datosForm = $this->datosFormularioSolicitud($tarea);
#        $case = $tarea->{"caseId"};
#      	$vc = $this->getVariableValue($case,'id_videoconferencia');
      } else {
    		$idTarea = "No se paso bien el id";
	    }
    	return view('finalizarVC', $datosForm);
    }

    public function enviarFinalizarVC(Request $request){
      $id_solicitante = $request->id_solicitante;
      $comentarios = $request->comentariosFin;
      $fecha = date('Y-m-d');
      $hora = date("h:i:s");
      $id_vc = $request->id_vc;
      $tarea =  $request->id_tarea;
      $id_estado= $request->estadoFin;
      $comentarios=$request->comentariosFin;
      DB::table('registro_videoconferencia')->insertGetId(
            ['fecha' => $fecha, 'hora' => $hora, 'estado' => $id_estado, 'descripcion' => $comentarios , 'videoconferencia' => $id_vc]);


      $response = GuzzleController::requestBonita('PUT','API/bpm/task/'.$request->id_tarea,['state' => 'completed']);
    }













}
