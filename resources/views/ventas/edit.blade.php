@extends("crudbooster::admin_template")
@section('content')

<div class="panel panel-primary">
    <div class="panel-heading text-center">
        <strong>EDITAR ENCABEZADO DE LA VENTA </strong>
    </div>
    <br>

    @if ($errors->any())
    <div class="alert alert-danger" role="alert">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>

            @endforeach
        </ul>
    </div>
    @endif

    <div class="panel panel-success">
        <div class="panel-heading text-center"  style="height: 30px;padding:0;">
            <strong>ENCABEZADO DE LA VENTA</strong>
        </div>
    <div class="panel-body align-content-center">
        <form method="POST" action="/admin/ventas/update/{{$venta->id}}">
            @method('PATCH')
            @csrf()
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>***CLIENTE:***</label>
                            <select id="slCliente" name="slCliente" class="form-control selectpicker" data-live-search="true">
                                    @foreach ($clientes as $cliente)
                                            @if ($cliente->id==$venta->cliente_id)
                                            <option value="{{$cliente->id}}" selected>{{$cliente->nombres}}</option>
                                            @else
                                            <option value="">...Busque y Seleccione el Cliente...</option>
                                            <option value="{{$cliente->id}}">{{$cliente->nombres}}</option>
                                            @endif
                                    @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>***NÚMERO DE FACTURA***</label>
                            <input type="text" class="form-control" name="txtFactura" id="txtFactura" value="{{ $venta->num_factura }}">
                        </div>
                    </div>


                    <div class="col-md-6">
                        <div class="form-group">
                            <label>***FECHA Y HORA DE VENTA***</label>
                            <input type="datetime-local" class="form-control" name="txtFechaVenta" id="txtFechaVenta"
                            value="{{ $venta->fecha_venta }}">
                        </div>
                    </div>

                    <div class="col-md-12 ">
                        <div class="form-group">
                            <label>OBSERVACIONES</label>
                            <textarea class="form-control" name="txtObservaciones" id="txtObservaciones" cols="30" rows="5" >{{$venta->observaciones}}</textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <input type="hidden" class="form-control" name="txtResponsableEdicion" id="txtResponsableEdicion" value={{ $responsable_edicion}}>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12">
                        <div class="form-group">
                            <button type="submit" class="btn btn-success btn-block"><strong>Editar Encabezado de la venta</strong></button>
                        </div>
                </div>
            </div>
        </form>
    </div>
            <div class="panel panel-success">
                <div class="panel-heading text-center"  style="height: 30px;padding:0;">
                    <strong>DETALLES DE LOS PRODUCTOS EN LA VENTA</strong>
                </div>
                    <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-1 col-sm-1 col-md-1 col-xs-12">
                                    <div class="form-group text-center">
                                        <a href="" data-target="#modal-add-ventas" data-toggle="modal">
                                            <button class="btn btn-success fa fa-plus">Agregar Producto</button>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                                <div class="table-responsive">
                                    <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                                        <thead style="background-color:#A9D0F5">
                                            <th>Producto</th>
                                            <th>Precio</th>
                                            <th>Cantidad</th>
                                            <th>Observaciones</th>
                                            <th>Subtotal</th>
                                            <th>Acción</th>
                                        </thead>
                                        <tfoot>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th>TOTAL VENTA</th>
                                            <th><h4 id="total"><strong>{{number_format($venta->total_venta)}}</strong></h4></th>
                                            <th></th>

                                        </tfoot>
                                        @foreach($ventaDetalles as  $detalles)
                                        <tbody>
                                            <tr>
                                            <td scope="row">{{$detalles->nombre_producto}}</td>
                                            <td scope="row">{{$detalles->precio_venta}}</td>
                                            <td scope="row">{{$detalles->cantidad}}</td>
                                            <td scope="row">{{$detalles->observaciones}}</td>
                                            <td scope="row">{{$detalles->cantidad*$detalles->precio_venta}}</td>
                                            <td>
                                                <a href="" data-target="#modal-delete-{{$detalles->id}}" data-toggle="modal">
                                                    <button class="btn btn-danger fa fa-trash"></button>
                                                </a>
                                            </td>
                                            </tr>
                                            @include('ventas.modal')
                                        </tbody>
                                        @endforeach
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer text-center" id="guardar">
                            <a href="/admin/ventas" class="btn btn-danger">Volver al Listado de Ventas</a>
                        </div>
                    </div>
            </div>
    @include('sweetalert::alert')

@endsection

@section('custom_script')

    <script>

         $("#pslProducto").change(mostrarValores);

            function mostrarValores()
            {
                datosProducto=document.getElementById('pslProducto').value.split('_');

                $("#ptxtIdProducto").val(datosProducto[0]);
                $("#ptxtReferencia").val(datosProducto[1]);
                $("#ptxtDisponible").val(datosProducto[2]);
                $("#ptxtUltimoPrecio").val(datosProducto[3]);

                console.log(datosProducto)

            }



    </script>

@endsection
