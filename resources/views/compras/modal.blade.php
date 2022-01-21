<!-- MODAL ADD PRODUCTOS COMPRAS -->
<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modal-add-compras">
    <form action="/admin/compras-detalles" method="POST">
        @csrf
        <div class="modal-dialog">
            <div class="modal-content modal-lg text-center">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">X</span>
                    </button>
                    <h3 class="modal-title"><strong> Agregar nuevo Producto a la Compra</strong></h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="panel panel-primary">
                            <div class="panel-heading text-center">
                                <h4>DETALLES DEL PRODUCTO</h4>
                            </div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>***PRODUCTO***</label>
                                        <select name="pslProducto" id="pslProducto" class="form-control"
                                        style="width: 100%" required>
                                            <option value="">---Busque y Seleccione el Producto---</option>
                                            @foreach ($productos as $producto)
                                            <option value="{{$producto->id}}">{{$producto->nombre}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>***PRECIO UNITARIO***</label>
                                        <input type="number" name="ptxtPrecioCompra" id="ptxtPrecioCompra" step="0.001"
                                            min="0.001" class="form-control" placeholder="Precio" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>***CANTIDAD A INGRESAR***</label>
                                        <input type="number" name="ptxtCantidad" id="ptxtCantidad" step="0.001"
                                            min="0.001" class="form-control" placeholder="Cantidad" required>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>OBSERVACIONES</label>
                                        <textarea class="form-control" name="ptxtObservacionesDetalles"
                                            id="ptxtObservacionesDetalles" cols="30" rows="5"
                                            placeholder="Observaciones generales del Producto"></textarea>
                                    </div>
                                </div>

                                <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                                    <div class="form-group">
                                        <input type="hidden" name="ptxtIdCompra" id="ptxtIdCompra"
                                            class="form-control" value="{{$detalles->compra_id}}">
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

<!-- MODAL DELETE PRODUCTOS COMPRAS -->
<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1"
    id="modal-delete-{{$detalles->id}}">
    <form action="/admin/compras/delete/{{$detalles->id}}" method="POST">
        @method('DELETE')
        @csrf
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">X</span>
                    </button>
                    <h3 class="modal-title"><strong>¿Desea Eliminar el Producto de la Compra?</strong></h3>
                </div>
                <div class="modal-body">
                    <h4><strong>Confirme si de desea Eliminar el Producto de la Compra , ¡no podrá Recuperarlo!</strong>
                    </h4>
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

