@extends("crudbooster::admin_template")
@section('content')

<div class="panel panel-primary">
    <div class="panel-heading text-center">
        <strong>REGISTRAR NUEVA VENTA </strong>
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
        <form action="/admin/ventas" method="post">
            {{ csrf_field() }}

            <div class="row">
                <div class="panel panel-success">
                    <div class="panel-heading text-center"  style="height: 30px;padding:0;">
                        <strong>ENCABEZADO DE LA VENTA</strong>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>***CLIENTE:***</label>
                                    <select name="slCliente" id="slCliente"
                                        class="form-control selectpicker" data-live-search="true" required>
                                        <option value="">...Busque y Seleccione el Cliente...</option>
                                        @foreach ($clientes as $cliente)
                                        <option value="{{$cliente->id}}">{{$cliente->nombres}}</option>
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
                                    <label>***FECHA Y HORA DE VENTA***</label>
                                    <input type="datetime-local" class="form-control" name="txtFechaVenta" id="txtFechaVenta"
                                        required>
                                </div>
                            </div>


                            <div class="col-md-12 ">
                                <div class="form-group">
                                    <label>OBSERVACIONES</label>
                                    <textarea class="form-control" name="txtObservaciones" id="txtObservaciones" cols="30" rows="5"
                                        placeholder="Ingrese las Observaciones de la Venta"></textarea>
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
                    </div>
                </div>

                <div class="row">
                    <div class="panel panel-success">

                        <div class="panel-heading text-center" style="height: 30px;padding:0;">
                            <strong>DETALLES DE LA VENTA </strong>
                        </div>

                        <div class="panel-body">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>***PRODUCTO***</label>
                                    <select name="slProducto" id="slProducto" class="form-control selectpicker"
                                        data-live-search="true">
                                        <option value="">---Busque y Seleccione el Producto---</option>
                                        @foreach ($productos as $producto)
                                        <option
                                            value="{{$producto->id}}_{{$producto->referencia}}_{{$producto->stock}}_{{$producto->ultimo_precio}}">
                                            {{$producto->producto_completo}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>***REFERENCIA***</label>
                                    <input type="text" name="txtReferencia" id="txtReferencia" step="0.001" min="0.001"
                                        class="form-control" placeholder="Referencia" readonly="true">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>***STOCK***</label>
                                    <input type="number" name="txtStock" id="txtStock" step="0.001" min="0.001"
                                        class="form-control" placeholder="Stock" readonly="true">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>***PRECIO UNITARIO***</label>
                                    <input type="number" name="txtPrecio" id="txtPrecio" step="0.001" min="0.001"
                                        class="form-control" placeholder="Precio Venta" readonly="true">
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
                                        placeholder="Ingrese las Observaciones del Producto"></textarea>
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
                                        <th>TOTAL VENTA</th>
                                        <th>
                                            <h4 id="total">$0.00</h4><input type="hidden" name="total_venta"
                                                id="total_venta">
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
                    <a href="/admin/ventas" class="btn btn-danger">Volver al listado de Ventas</a>
                    <button type="submit" class="btn btn-success">Guardar Nueva Venta</button>
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

                $("#txtReferencia").val(valoresProducto[1]);
                $("#txtStock").val(valoresProducto[2]);
                $("#txtPrecio").val(valoresProducto[3]);

                //console.log(valoresProducto)

            }

            function agregar()
            {

                datosProducto=document.getElementById('slProducto').value.split('_');
                idproducto=datosProducto[0];
                producto=$("#slProducto option:selected").text();
                stock=$("#txtStock").val();
                disponible = parseFloat(stock);
                precio=$("#txtPrecio").val();
                cantidad=$("#txtCantidad").val();
                cantidadSalida = parseFloat(cantidad);
                observaciones=$("#txtObservacionesDetalles").val();

                console.log(disponible , cantidadSalida)



                if (idproducto!="" && cantidad!="" && cantidad>0 && precio!="" && stock!="" )
                {

                    if(cantidadSalida <= disponible)
                    {

                    subtotal[cont]=(cantidadSalida*precio);
                    total=total+subtotal[cont];

                    var fila='<tr class="selected" id="fila'+cont+'"><td><button type="button" class="btn btn-danger" onclick="eliminar('+cont+')">X</button></td><td><input type="hidden" name="idproducto[]" value="'+idproducto+'">'+producto+'</td><td><input type="number" readonly="true" name="precio[]" value="'+precio+'"</td><td><input type="number" readonly="true" name="cantidad[]" value="'+cantidad+'"</td><td><input type="text" name="observaciones[]" value="'+observaciones+'"</td><td>'+subtotal[cont]+'</td></tr>';
                    cont++;
                    limpiar();
                    $("#total").html("$/. " + total);
                    $("#total_venta").val(total);
                    evaluar();
                    $('#detalles').append(fila);
                    }
                    else
                    {
                        alert("La *CANTIDAD* de salida del Producto es mayor a la *DISPONIBLE* en almacén, por favor verifique!");
                    }
                }
                else
                {
                    alert("Error al ingresar el detalle de la Venta, revise los datos del Producto");
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
            $("#total").html("$/. " + total);
            $("#total_venta").val(total);
            $("#fila" + index).remove();
            evaluar();


            }

</script>


@endsection
