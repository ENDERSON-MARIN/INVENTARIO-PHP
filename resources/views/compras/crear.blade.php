@extends("crudbooster::admin_template")
@section('content')

<div class="panel panel-warning">
    <div class="panel-heading text-center" style="height: 30px;padding:0;">
        <strong>ENCABEZADO DE LA COMPRA </strong>
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
        <form action="/admin/compras" method="post">
            {{ csrf_field() }}
            <div class="row">

                <div class="col-md-12">
                    <div class="form-group">
                        <label>***PROVEEDOR***</label>
                        <select name="slProveedor" id="slProveedor" class="form-control selectpicker"
                            data-live-search="true" required>
                            <option value="">...Busque y Seleccione el Proveedor...</option>
                            @foreach ($proveedores as $proveedor)
                            <option value="{{$proveedor->id}}">{{$proveedor->nombres}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>***NÚMERO DE FACTURA***</label>
                        <input type="text" class="form-control" name="txtFactura" id="txtFactura" required>
                    </div>
                </div>


                <div class="col-md-6">
                    <div class="form-group">
                        <label>***FECHA Y HORA DE COMPRA***</label>
                        <input type="datetime-local" class="form-control" name="txtFechaCompra" id="txtFechaCompra"
                            required>
                    </div>
                </div>


                <div class="col-md-12 ">
                    <div class="form-group">
                        <label>OBSERVACIONES</label>
                        <textarea class="form-control" name="txtObservaciones" id="txtObservaciones" cols="30" rows="5"
                            placeholder="Ingrese las Observaciones del Ingreso"></textarea>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <input type="hidden" class="form-control" name="txtResponsableCreacion" id="txtResponsableCreacion" value={{ $responsable_creacion }}>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="panel panel-warning">

                    <div class="panel-heading text-center" style="height: 30px;padding:0;">
                        <strong>DETALLES DE LA COMPRA</strong>
                    </div>

                    <div class="panel-body">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>***PRODUCTO***</label>
                                <select name="slProducto" id="slProducto" class="form-control selectpicker"
                                    data-live-search="true">
                                    <option value="">---Busque y Seleccione el producto a Ingresar---</option>
                                    @foreach ($productos as $producto)
                                    <option value="{{$producto->id}}_{{$producto->referencia}}_{{$producto->stock}}">
                                        {{$producto->producto_completo}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>***REFERENCIA***</label>
                                <input type="text" name="txtReferencia" id="txtReferencia" step="0.001" min="0.001"
                                    class="form-control" placeholder="Referencia" disabled>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>***STOCK***</label>
                                <input type="number" name="txtStock" id="txtStock" step="0.001" min="0.001"
                                    class="form-control" placeholder="Stock" disabled>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>***PRECIO UNITARIO***</label>
                                <input type="number" name="txtPrecio" id="txtPrecio" step="0.001" min="0.001"
                                    class="form-control" placeholder="Precio Compra">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>***CANTIDAD***</label>
                                <input type="number" name="txtCantidad" id="txtCantidad" step="0.001" min="0.001"
                                    class="form-control" placeholder="Cantidad">
                            </div>
                        </div>

                        <div class="col-md-10">
                            <div class="form-group">
                                <label>OBSERVACIONES</label>
                                <textarea class="form-control" name="txtObservacionesDetalles"
                                    id="txtObservacionesDetalles" cols="30" rows="5"
                                    placeholder="Ingrese las Observaciones del Ingreso"></textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group text-center">
                                    <button type="button" id="btn_add" class="btn btn-success btn-sm">Agregar Producto</button>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                            <div class="table-responsive">
                                <table id="detalles"
                                    class="table table-striped table-bordered table-condensed table-hover">
                                    <thead style="background-color:#A9D0F5">
                                        <th>Opciones</th>
                                        <th>Producto</th>
                                        <th>Precio</th>
                                        <th>Cantidad</th>
                                        <th>Observaciones</th>
                                        <th>Subtotal</th>
                                    </thead>
                                    <tfoot>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th>TOTAL COMPRA</th>
                                        <th>
                                            <h4 id="total">$0.00</h4><input type="hidden" name="total_compra"
                                                id="total_compra">
                                        </th>
                                    </tfoot>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer text-center" id="guardar">
                    <a href="/admin/compras" class="btn btn-danger">Volver al listado de Compras</a>
                    <button type="submit" class="btn btn-success">Guardar Nueva Compra</button>
                </div>

        </form>
    </div>
</div>

@include('sweetalert::alert')

@endsection

@section('custom_script')

    <script>

                $(document).ready(function() {
                    $('#btn_add').click(function() {
                        agregar();
                    });
                });

                var cont=0;
                total=0;
                subtotal=[];
                $("#guardar").hide();
                $("#slProducto").change(mostrarValores);

                function mostrarValores()
                {
                    valoresProducto=document.getElementById('slProducto').value.split('_');

                    //console.log(valoresProducto)
                    $("#txtReferencia").val(valoresProducto[1]);
                    $("#txtStock").val(valoresProducto[2]);

                }

                function agregar()
                {

                    datosProducto=document.getElementById('slProducto').value.split('_');

                    idproducto=datosProducto[0];

                    console.log(idproducto)

                    producto=$("#slProducto option:selected").text();
                    cantidad=$("#txtCantidad").val();
                    precio=$("#txtPrecio").val();
                    observaciones=$("#txtObservacionesDetalles").val();

                    if (idproducto!="" && cantidad!="" && cantidad>0 && precio!="")
                    {

                        subtotal[cont]=(cantidad*precio);
                        total=total+subtotal[cont];

                        var fila='<tr class="selected" id="fila'+cont+'"><td><button type="button" class="btn btn-danger" onclick="eliminar('+cont+')">X</button></td><td><input type="hidden" name="idproducto[]" value="'+idproducto+'">'+producto+'</td><td><input type="number" name="precio[]" value="'+precio+'" readonly="true"</td><td><input type="number" name="cantidad[]" value="'+cantidad+'" readonly="true"</td><td><input type="text" name="observaciones[]" value="'+observaciones+'"</td><td>'+subtotal[cont]+'</td></tr>';
                        cont++;
                        limpiar();
                        $("#total").html("$" + total);
                        evaluar();
                        $('#detalles').append(fila);
                    }
                    else
                    {
                        alert("Error al ingresar el detalle del ingreso, Verifique los datos nuevamente!");
                    }
                }


                function limpiar() {
                    $("#slProducto").val("");
                    $("#txtReferencia").val("");
                    $("#txtStock").val("");
                    $("#txtPrecio").val("");
                    $("#txtCantidad").val("");
                    $("#txtObservacionesDetalles").val("");
                }


                function evaluar()
                {
                    if (total>0)
                    {
                        $("#guardar").show();
                    }
                    else
                    {
                        $("#guardar").hide();
                    }
                }

                function eliminar(index){
                total=total-subtotal[index];
                $("#total").html("$" + total);
                $("#fila" + index).remove();
                evaluar();


                }

    </script>

@endsection
