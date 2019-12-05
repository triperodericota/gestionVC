    private function obtenerVC($id){
      $uri = 'API/bpm/case/'.$id;
      $response = GuzzleController::requestBonita('GET',$uri);
      $tarea = json_decode($response);
      return $tarea;

    }


    public function enviarInicioVC(Request $request){
      #if($request->id_solicitante == null){
        #$response = GuzzleController::requestBonita('GET','API/bpm/case/'.$request->id_case);
        #$id_solicitante = json_decode($response)->started_by;
      #}else{
        $id_solicitante = $request->id_solicitante;
      #}


      $fecha = date('d/m/Y'); /* sin timezone */
      echo $fecha;
      $hora = date("h:i:sa");
      echo $hora;
      
      $tarea =  $request->id_tarea;
      echo $tarea;

      $comentarios = $request->ComentariosInicio;
      echo $comentarios;


      $response = GuzzleController::requestBonita('PUT','API/bpm/task/'.$request->id_tarea,['state' => 'completed']);
      #:wecho(var_dump($response));
    }

    	}
    	return view('solicitudVC', $datosForm);
    }



    
    public function inicioVC(){
    /*  if (isset($_GET['id'])) {
    		$idTarea = $_GET['id'];
        $tarea = $this->obtenerTarea($idTarea);
        $datosForm = $this->datosFormularioSolicitud($tarea);
    	} else {
    		$idTarea = "No se paso bien el id";
	}*/
        $tarea = $this->obtenerTarea($idTarea);
	$case = $tarea->{"caseId"};
	$vc = obtenerVC($case);
	$datosForm = ['idTarea' => "123"];
    	return view('inicioVC', $datosForm);
    }

