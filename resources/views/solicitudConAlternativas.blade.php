@extends('layout')

<body>
    <div class="flex-center position-ref full-height">
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                  <!-- left column -->
                    <div>
                      <p> Id Task:  {{ $idTarea }} </p>
                   <!--     <p> Alternativas: {{ $alternativas }}</p> -->

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
                                  <label for="unidad">Unidad</label> <!-- pasarle un array o list con los nombres de las unidades y que lo itere acá -->
                                  <select required class="form-control" id="id_unidad" name="id_unidad">
                                    @foreach ($unidades as $unidad):
                                      @if ($unidad['id'] == $id_unidad):
                                        <option selected value="{{ $unidad['id'] }}" name="{{ $unidad['id'] }}">{{ $unidad['nombre'] }}</option>
                                      @else
                                        <option value="{{ $unidad['id'] }}" name="{{ $unidad['id'] }}">{{ $unidad['nombre'] }}</option>
                                      @endif
                                    @endforeach
                                  </select>
                              </div>
                              <div class="form-group">
                                  <label for="fecha">Fecha</label>
                                  <input type="text" required class="form-control" id="fecha" name="fecha" value="{{ $fecha ?? '10/12/2019' }}" placeholder="">
                              </div>
                              <div class="form-group">
                                  <label for="fecha">Hora</label>
                                  <input type="text" required class="form-control" id="hora" name="hora" value="{{ $hora ?? '10:00'}}" placeholder="">
                              </div>
                                                         <!-- /.card-body -->
                            <input type="hidden" class="form-control" id="id_tarea" name="id_tarea" value="{{$idTarea}}">
                            <input type="hidden" class="form-control" id="id_actor" name="id_actor" value="{{$idActor}}">
                            <input type="hidden" class="form-control" id="id_case" name="id_case" value="{{$idCase}}">
                            <input type="hidden" class="form-control" id="id_solicitante" name="id_solicitante" value="{{$idUser}}">
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
