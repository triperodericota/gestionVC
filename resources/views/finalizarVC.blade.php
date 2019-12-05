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
                            <h3 class="card-title">Finalizacion de videoconferencia</h3>
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
                                  <label for="finalizarEstado">Finalizar con estado:</label>
                                  <select required class="form-control" id="finalizarEstado" name="finalizarEstado">
                                        <option value="1" name="Suspendida">suspendida</option>
                                        <option value="2" name="Finalizada en termino">finalizada en termino</option>
                                        <option value="3" name="Finalizada con demora">finalizada con demora</option>
                                        <option value="4" name="Interrumpida por problema tecnico">interrumpida por problema tecnico</option>
                                        <option value="5" name="Interrumpida por comportamiento del interno">interrumpida por comportamiento del interno</option>

                                  </select>
			      </div>

                              <div class="form-group">
                                <label for="Comentarios">Comentarios de desarrollo y finalización</label>
                                <textarea required class="form-control" id="ComFin" name="ComentariosFin" > </textarea>
                              </div>



                            </div>
                            <!-- /.card-body -->
                            <input type="hidden" class="form-control" id="id_tarea" name="id_tarea" value="{{$idTarea ?? ''}}">
                            @csrf
                            <div class="card-footer">
                              <button type="submit" class="btn btn-primary">Finalizar VC</button>
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
