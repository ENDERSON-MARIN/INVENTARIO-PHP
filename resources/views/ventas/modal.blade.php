<!-- MODAL ADD PRODUCTOS VENTAS -->

<div class="modal fade modal-slide-in-right" aria-hidden="true"
    role="dialog" tabindex="-1" id="modal-add-ventas">
    <form action="/admin/ventas-detalles" method="POST">
        @csrf
        <div class="modal-dialog">
            <div class="modal-content modal-lg text-center">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"
                    aria-label="Close">
                        <span aria-hidden="true">X</span>
                    </button>
                    <h3 class="modal-title"><strong> Agregar Nuevo Producto a la Venta</strong></h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="panel panel-primary">
                            <div class="panel-heading text-center">
                                <h4>DETALLES DE PRODUCTO</h4>
                            </div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>***PRODUCTO***</label>
                                        <select name="pslProducto" id="pslProducto" class="form-control style="width: 100%" required>
                                            <option value="">---Busque y Seleccione el Material---</option>
                                            @foreach ($productos as $producto)
                                            <option value="{{$producto->id}}_{{$producto->referencia}}_{{$producto->stock}}_{{$producto->ultimo_precio}}">{{$producto->producto_completo}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>***REFERENCIA***</label>
                                        <input type="text" name="ptxtReferencia" id="ptxtReferencia"  step="0.001" class="form-control" placeholder="Referencia" readonly="true">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>***STOCK-DISPONIBLE***</label>
                                        <input type="number" name="ptxtDisponible" id="ptxtDisponible"  step="0.001" class="form-control" placeholder="Stock-Disponible" readonly="true">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>***PRECIO DEL PRODUCTO***</label>
                                        <input type="number" name="ptxtUltimoPrecio" id="ptxtUltimoPrecio"  step="0.001" class="form-control" placeholder="Precio Producto" readonly="true">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>***CANTIDAD***</label>
                                        <input type="number" name="ptxtCantidad" id="ptxtCantidad"  step="0.001" class="form-control" placeholder="Cantidad a Verder" required>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>OBSERVACIONES</label>
                                        <textarea class="form-control" name="ptxtObservacionesDetalles" id="ptxtObservacionesDetalles" cols="30" rows="5" placeholder="Ingrese las Observaciones del producto"></textarea>
                                    </div>
                                </div>


                                <div class="col-lg-4 col-sm-4 col-md-4 col-xs-4">
                                    <div class="form-group">
                                        <input type="hidden" name="ptxtIdProducto" id="ptxtIdProducto" class="form-control" readonly="true">
                                    </div>
                                </div>

                                <div class="col-lg-4 col-sm-4 col-md-4 col-xs-4">
                                    <div class="form-group">
                                        <input type="hidden" name="ptxtIdVenta" id="ptxtIdVenta" class="form-control" value="{{$detalles->venta_id}}" readonly="true">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-sm-6 text-left">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                        </div>

                        <div class="col-sm-6 text-right">
                            <button type="submit" class="btn btn-success">Agregar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- MODAL DELETE PRODUCTOS VENTAS -->

<div class="modal fade modal-slide-in-right" aria-hidden="true"
role="dialog" tabindex="-1" id="modal-delete-{{$detalles->id}}">
    <form action="/admin/ventas/delete/{{$detalles->id}}" method="POST">
        @method('DELETE')
        @csrf
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"
                    aria-label="Close">
                        <span aria-hidden="true">X</span>
                    </button>
                    <h3 class="modal-title"><strong>¿Desea Eliminar el Detalle de la Venta?</strong></h3>
                </div>
                <div class="modal-body">
                <h4><strong>Confirme si de desea Eliminar el Detalle de la Venta , ¡no podrá Recuperarlo!</strong></h4>
                </div>
                <div class="modal-footer">
                <div class="row">
                    <div class="col-sm-6 text-left">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                    </div>
                    <div class="col-sm-6 text-right">
                        <button type="submit" class="btn btn-success">Confirmar</button>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </form>
</div>



