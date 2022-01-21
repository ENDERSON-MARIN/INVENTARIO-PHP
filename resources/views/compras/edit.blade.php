@extends("crudbooster::admin_template")
@section('content')

<div class="panel panel-warning">
    <div class="panel-heading text-center" style="height: 30px;padding:0;">
        <strong>EDITAR ENCABEZADO DE COMPRA </strong>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger" role="alert">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>

            @endforeach
        </ul>
    </div>
    @endif

    <div class="panel-body align-content-center">
        <form method="POST" action="/admin/compras/update/{{$compra->id}}">
            @method('PATCH')
            @csrf()
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>***PROVEEDOR***</label>
                        <select id="slProveedor" name="slProveedor" class="form-control selectpicker"
                            data-live-search="true">
                            @foreach ($proveedores as $proveedor)
                            @if ($proveedor->id==$compra->proveedor_id)
                            <option value="{{$proveedor->id}}" selected>{{$proveedor->nombres}}</option>
                            @else
                            <option value="">...Busque y Seleccione el Proveedor...</option>
                            <option value="{{$proveedor->id}}">{{$proveedor->nombres}}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>***NÚMERO DE FACTURA***</label>
                        <input type="text" class="form-control" name="txtFactura" id="txtFactura"
                            value="{{$compra->num_factura}}">
                    </div>
                </div>


                <div class="col-md-6 ">
                    <div class="form-group">
                        <label>***FECHA Y HORA DEL PEDIDO***</label>
                        <input type="text" class="form-control" name="txtFechaCompra" id="txtFechaCompra"
                            value="{{$compra->fecha_compra}}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 ">
                    <div class="form-group">
                        <label>OBSERVACIONES</label>
                        <textarea class="form-control" name="txtObservaciones" id="txtObservaciones" cols="30"
                            rows="5">{{$compra->observaciones}}</textarea>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <input type="hidden" class="form-control" name="txtResponsableEdicion"
                            id="txtResponsableEdicion" value={{ $responsable_edicion }}>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <button type="submit" class="btn btn-success btn-block"><strong>Editar Encabezado de la Compra</strong></button>
                </div>
            </div>
    </div>
    </form>
</div>
<div class="panel panel-warning">
    <div class="panel-heading text-center" style="height: 30px;padding:0;">
        <strong>EDITAR DETALLES DE LA COMPRA </strong>
    </div>

    <div class="panel-body">
        <div class="row">
            <div class="col-lg-1 col-sm-1 col-md-1 col-xs-12">
                <div class="form-group text-center">
                    <a href="" data-target="#modal-add-compras" data-toggle="modal">
                        <button class="btn btn-success fa fa-plus">Agregar Nuevo Producto</button>
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
                        <th>TOTAL COMPRA</th>
                        <th>
                            <h4 id="total"><strong>{{number_format($compra->total_compra)}}</strong></h4>
                        </th>
                        <th></th>

                    </tfoot>
                    @foreach($compraDetalles as $detalles)
                    <tbody>
                        <tr>
                            <td scope="row">{{$detalles->nombre_producto}}</td>
                            <td scope="row">{{number_format($detalles->precio_compra)}}</td>
                            <td scope="row">{{$detalles->cantidad}}</td>
                            <td scope="row">{{$detalles->observaciones}}</td>
                            <td scope="row">{{number_format($detalles->cantidad*$detalles->precio_compra)}}</td>
                            <td>
                                <a href="" data-target="#modal-delete-{{$detalles->id}}" data-toggle="modal">
                                    <button class="btn btn-danger fa fa-trash"></button>
                                </a>
                            </td>
                        </tr>
                        @include('compras.modal')
                    </tbody>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
    <div class="panel-footer text-center" id="guardar">
        <a href="/admin/compras" class="btn btn-danger">Volver al Listado de Compras</a>
    </div>
</div>
</div>
@include('sweetalert::alert')

@endsection

@section('custom_script')

<script>

</script>

@endsection
