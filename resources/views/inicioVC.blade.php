@extends('layout')

<body>
    <div class="flex-center position-ref full-height">
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                  <!-- left column -->
                    <div>
                      <p> Id Task:  {{ $idTarea ?? '' }} </p>
                        <!-- general form elements -->
                        <div class="card card-primary">
                          <div class="card-header">
                            <h3 class="card-title">Inicio de videoconferencia</h3>
                          </div>
                          <!-- /.card-header -->
                          <!-- form start -->
                          <form role="form" method="POST">
                            <div class="card-body">
                              <div class="form-group">
                                <label for="nro_causa">N&uacutemero de causa</label>
                                <input type="text" required class="form-control" id="nro_causa" name="nro_causa" value="22222" placeholder="">
                              </div>




                              <div class="form-group">
                                  <label for="participante1">Iniciar con estado:</label>
                                  <select required class="form-control" id="estadoInicio" name="estadoInicio">
                                        <option value="1" name="Iniciada en termino">Iniciada en termino</option>
                                        <option value="2" name="Iniciada con demora">Iniciada con demora</option>
                                  </select>
			      </div>

                              <div class="form-group">
                                <label for="Comentarios">Comentarios</label>
                                <textarea required class="form-control" id="ComInicio" name="ComentariosInicio" > </textarea>
                              </div>



                            </div>
                            <!-- /.card-body -->
                            <input type="hidden" class="form-control" id="id_tarea" name="id_tarea" value="{{$idTarea}}">
                            <input type="hidden" class="form-control" id="id_vc" name="id_vc" value="{{$idVC}}">
                            @csrf
                            <div class="card-footer">
                              <button type="submit" class="btn btn-primary">Iniciar VC</button>
                            </div>
                          </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</body>
</html>
