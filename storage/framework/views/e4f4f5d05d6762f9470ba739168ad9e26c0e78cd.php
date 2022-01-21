<?php $__env->startSection('content'); ?>

<div class="panel panel-warning">
    <div class="panel-heading text-center" style="height: 30px;padding:0;">
        <strong>EDITAR ENCABEZADO DE COMPRA </strong>
    </div>

    <?php if($errors->any()): ?>
    <div class="alert alert-danger" role="alert">
        <ul>
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li><?php echo e($error); ?></li>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
    <?php endif; ?>

    <div class="panel-body align-content-center">
        <form method="POST" action="/admin/compras/update/<?php echo e($compra->id); ?>">
            <?php echo method_field('PATCH'); ?>
            <?php echo csrf_field(); ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>***PROVEEDOR***</label>
                        <select id="slProveedor" name="slProveedor" class="form-control selectpicker"
                            data-live-search="true">
                            <?php $__currentLoopData = $proveedores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proveedor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($proveedor->id==$compra->proveedor_id): ?>
                            <option value="<?php echo e($proveedor->id); ?>" selected><?php echo e($proveedor->nombres); ?></option>
                            <?php else: ?>
                            <option value="">...Busque y Seleccione el Proveedor...</option>
                            <option value="<?php echo e($proveedor->id); ?>"><?php echo e($proveedor->nombres); ?></option>
                            <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>***NÚMERO DE FACTURA***</label>
                        <input type="text" class="form-control" name="txtFactura" id="txtFactura"
                            value="<?php echo e($compra->num_factura); ?>">
                    </div>
                </div>


                <div class="col-md-6 ">
                    <div class="form-group">
                        <label>***FECHA Y HORA DEL PEDIDO***</label>
                        <input type="text" class="form-control" name="txtFechaCompra" id="txtFechaCompra"
                            value="<?php echo e($compra->fecha_compra); ?>">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 ">
                    <div class="form-group">
                        <label>OBSERVACIONES</label>
                        <textarea class="form-control" name="txtObservaciones" id="txtObservaciones" cols="30"
                            rows="5"><?php echo e($compra->observaciones); ?></textarea>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <input type="hidden" class="form-control" name="txtResponsableEdicion"
                            id="txtResponsableEdicion" value=<?php echo e($responsable_edicion); ?>>
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
                            <h4 id="total"><strong><?php echo e(number_format($compra->total_compra)); ?></strong></h4>
                        </th>
                        <th></th>

                    </tfoot>
                    <?php $__currentLoopData = $compraDetalles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detalles): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tbody>
                        <tr>
                            <td scope="row"><?php echo e($detalles->nombre_producto); ?></td>
                            <td scope="row"><?php echo e(number_format($detalles->precio_compra)); ?></td>
                            <td scope="row"><?php echo e($detalles->cantidad); ?></td>
                            <td scope="row"><?php echo e($detalles->observaciones); ?></td>
                            <td scope="row"><?php echo e(number_format($detalles->cantidad*$detalles->precio_compra)); ?></td>
                            <td>
                                <a href="" data-target="#modal-delete-<?php echo e($detalles->id); ?>" data-toggle="modal">
                                    <button class="btn btn-danger fa fa-trash"></button>
                                </a>
                            </td>
                        </tr>
                        <?php echo $__env->make('compras.modal', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                    </tbody>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </table>
            </div>
        </div>
    </div>
    <div class="panel-footer text-center" id="guardar">
        <a href="/admin/compras" class="btn btn-danger">Volver al Listado de Compras</a>
    </div>
</div>
</div>
<?php echo $__env->make('sweetalert::alert', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('custom_script'); ?>

<script>

</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make("crudbooster::admin_template", \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>