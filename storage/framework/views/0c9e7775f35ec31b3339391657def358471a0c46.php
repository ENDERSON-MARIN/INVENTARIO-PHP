<?php $__env->startSection('content'); ?>

<div class="panel panel-primary">
    <div class="panel-heading text-center">
        <strong>EDITAR ENCABEZADO DE LA VENTA </strong>
    </div>
    <br>

    <?php if($errors->any()): ?>
    <div class="alert alert-danger" role="alert">
        <ul>
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li><?php echo e($error); ?></li>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
    <?php endif; ?>

    <div class="panel panel-success">
        <div class="panel-heading text-center"  style="height: 30px;padding:0;">
            <strong>ENCABEZADO DE LA VENTA</strong>
        </div>
    <div class="panel-body align-content-center">
        <form method="POST" action="/admin/ventas/update/<?php echo e($venta->id); ?>">
            <?php echo method_field('PATCH'); ?>
            <?php echo csrf_field(); ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>***CLIENTE:***</label>
                            <select id="slCliente" name="slCliente" class="form-control selectpicker" data-live-search="true">
                                    <?php $__currentLoopData = $clientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cliente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($cliente->id==$venta->cliente_id): ?>
                                            <option value="<?php echo e($cliente->id); ?>" selected><?php echo e($cliente->nombres); ?></option>
                                            <?php else: ?>
                                            <option value="">...Busque y Seleccione el Cliente...</option>
                                            <option value="<?php echo e($cliente->id); ?>"><?php echo e($cliente->nombres); ?></option>
                                            <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>***NÚMERO DE FACTURA***</label>
                            <input type="text" class="form-control" name="txtFactura" id="txtFactura" value="<?php echo e($venta->num_factura); ?>">
                        </div>
                    </div>


                    <div class="col-md-6">
                        <div class="form-group">
                            <label>***FECHA Y HORA DE VENTA***</label>
                            <input type="datetime-local" class="form-control" name="txtFechaVenta" id="txtFechaVenta"
                            value="<?php echo e($venta->fecha_venta); ?>">
                        </div>
                    </div>

                    <div class="col-md-12 ">
                        <div class="form-group">
                            <label>OBSERVACIONES</label>
                            <textarea class="form-control" name="txtObservaciones" id="txtObservaciones" cols="30" rows="5" ><?php echo e($venta->observaciones); ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <input type="hidden" class="form-control" name="txtResponsableEdicion" id="txtResponsableEdicion" value=<?php echo e($responsable_edicion); ?>>
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
                                            <th><h4 id="total"><strong><?php echo e(number_format($venta->total_venta)); ?></strong></h4></th>
                                            <th></th>

                                        </tfoot>
                                        <?php $__currentLoopData = $ventaDetalles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detalles): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tbody>
                                            <tr>
                                            <td scope="row"><?php echo e($detalles->nombre_producto); ?></td>
                                            <td scope="row"><?php echo e($detalles->precio_venta); ?></td>
                                            <td scope="row"><?php echo e($detalles->cantidad); ?></td>
                                            <td scope="row"><?php echo e($detalles->observaciones); ?></td>
                                            <td scope="row"><?php echo e($detalles->cantidad*$detalles->precio_venta); ?></td>
                                            <td>
                                                <a href="" data-target="#modal-delete-<?php echo e($detalles->id); ?>" data-toggle="modal">
                                                    <button class="btn btn-danger fa fa-trash"></button>
                                                </a>
                                            </td>
                                            </tr>
                                            <?php echo $__env->make('ventas.modal', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                                        </tbody>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer text-center" id="guardar">
                            <a href="/admin/ventas" class="btn btn-danger">Volver al Listado de Ventas</a>
                        </div>
                    </div>
            </div>
    <?php echo $__env->make('sweetalert::alert', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('custom_script'); ?>

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

<?php $__env->stopSection(); ?>

<?php echo $__env->make("crudbooster::admin_template", \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>