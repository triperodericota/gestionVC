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
                                <label for="id_interno">ID interno</label> <!-- AGREGAR ESTA COLUMNA A LA TABLA INTERNO -->
                                <!-- <small class="form-text text-muted">Ingrese el n&uacutemero de DNI sin puntos(.)</small> -->
                                <input type="text" required class="form-control" id="id_interno" name="id_interno" value="1" placeholder="">
                              </div>
                              <div class="form-group">
                                <label for="nro_causa">N&uacutemero de causa</label>
                                <input type="text" required class="form-control" id="nro_causa" name="nro_causa" value="22222" placeholder="">
                              </div>
                              <div class="form-group">
                                  <label for="unidad">Unidad</label> <!-- pasarle un array o list con los nombres de las unidades y que lo itere acá -->
                                  <select required class="form-control" id="id_unidad" name="id_unidad">
                                    @foreach ($unidades as $unidad):
                                      <option value="{{ $unidad['id'] }}" name="{{ $unidad['id'] }}">{{ $unidad['nombre'] }}</option>
                                    @endforeach
                                  </select>
                              </div>
                              <div class="form-group">
                                  <label for="fecha">Fecha</label>
                                  <input type="text" required class="form-control" id="fecha" name="fecha" value="10/12/2019" placeholder="">
                              </div>
                              <div class="form-group">
                                  <label for="fecha">Hora</label>
                                  <input type="text" required class="form-control" id="hora" name="hora" value="10:00" placeholder="">
                              </div>
                              <div class="form-group">
                                <label for="motivo">Motivo</label>
                                <textarea required class="form-control" id="motivo" name="motivo" rows="3"></textarea>
                              </div>
                              <div class="form-group">
                                  <label for="participante1">Participante 1</label>
                                  <select required class="form-control" id="participante1" name="participante1">
                                    @foreach ($participantes as $participante):
                                        <option value="{{ $participante->{'id'} }}" name="{{ $participante->{'id'} }}">{{ $participante->{'lastname'} }}</option>
                                    @endforeach
                                  </select>
                              </div>
                              <div class="form-group">
                                  <label for="participante2">Participante 2</label>
                                  <select required class="form-control" id="participante2" name="participante2">
                                    @foreach ($participantes as $participante):
                                        <option value="{{ $participante->{'id'} }}" name="{{ $participante->{'id'} }}">{{ $participante->{'lastname'} }}</option>
                                    @endforeach
                                  </select>
                              </div>
                              <div class="form-group">
                                  <label for="participante3">Participante 3</label>
                                  <select required class="form-control" id="participante3" name="participante3">
                                    @foreach ($participantes as $participante):
                                      <option value="{{ $participante->{'id'} }}" name="{{ $participante->{'id'} }}">{{ $participante->{'lastname'} }}</option>
                                    @endforeach
                                  </select>
                              </div>
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
