@extends('layout')

<body>
    <div class="flex-center position-ref full-height">
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                  <!-- left column -->
                    <div>
                      <p> Id Task:  {{ $idTarea }} </p>
                        <!-- general form elements -->
                        <div class="card card-primary">
                          <div class="card-header">
                            <h3 class="card-title">Solicitud de videoconferencia</h3>
                          </div>
                          <!-- /.card-header -->
                          <!-- form start -->
                          <form role="form" method="POST">
                            <div class="card-body">
                              <div class="form-group">
                                <label for="dni_interno">DNI del interno</label> <!-- AGREGAR ESTA COLUMNA A LA TABLA INTERNO -->
                                <small class="form-text text-muted">Ingrese el n&uacutemero de DNI sin puntos(.)</small>
                                <input type="text" class="form-control" id="dni_interno" name="dni_interno" value="30000000" placeholder="">
                              </div>
                              <div class="form-group">
                                <label for="nro_causa">N&uacutemero de causa</label>
                                <input type="text" class="form-control" id="nro_causa" name="nro_causa" value="22222" placeholder="">
                              </div>
                              <div class="form-group">
                                  <label for="unidad">Unidad</label> <!-- pasarle un array o list con los nombres de las unidades y que lo itere acá -->
                                  <select class="form-control" id="unidad">

                                  </select>
                              </div>
                              <div class="form-group">
                                  <label for="fecha">Fecha</label>
                                  <input type="text" class="form-control" id="fecha" name="fecha" value="10/12/2019" placeholder="">
                              </div>
                              <div class="form-group">
                                  <label for="fecha">Hora</label>
                                  <input type="text" class="form-control" id="hora" name="hora" value="10:00" placeholder="">
                              </div>
                              <div class="form-group">
                                <label for="motivo">Motivo</label>
                                <textarea class="form-control" id="motivo" rows="3"></textarea>
                              </div>
                              <div class="form-group">
                                  <label for="participante1">Participante 1</label> <!-- pasarle un array o list con los Participantes y que lo itere acá -->
                                  <select class="form-control" id="participante1">
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                    <option>5</option>
                                  </select>
                              </div>
                            </div>
                            <!-- /.card-body -->
                            <input type="hidden" class="form-control" id="id_tarea" name="idTarea" value="{{$idTarea}}">
                            <input type="hidden" class="form-control" id="id_solicitante" name="id_solicitante" value="{{$idActor}}"> <!-- mandar id del usuario loggeado  -->
                            @csrf
                            <div class="card-footer">
                              <button type="submit" class="btn btn-primary">Enviar solicitud</button>
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
