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
                            <h3 class="card-title">Datos de la Venta</h3>
                          </div>
                          <!-- /.card-header -->
                          <!-- form start -->
                          <form role="form" method="POST">
                            <div class="card-body">
                              <div class="form-group">
                                <label for="articulo">Artículo</label>
                                <input type="text" class="form-control" id="articulo" name="articulo" value="Libros" placeholder="Ingrese Artículo">
                              </div>
                              <div class="form-group">
                                <label for="cantidad">Cantidad</label>
                                <input type="text" class="form-control" id="cantidad" name="cantidad" value="2" placeholder="Cantidad">
                              </div>
                              <div class="form-group">
                                <label for="precioUnitario">Precio Unitario</label>
                                <input type="text" class="form-control" id="precioUnitario" name="precioUnitario" value="300" placeholder="Precio Unitario">
                              </div>
                            </div>
                            <!-- /.card-body -->
                            <input type="hidden" class="form-control" id="idTarea" name="idTarea" value="{{$idTarea}}">
                            @csrf
                            <div class="card-footer">
                              <button type="submit" class="btn btn-primary">Realizar Venta</button>
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
