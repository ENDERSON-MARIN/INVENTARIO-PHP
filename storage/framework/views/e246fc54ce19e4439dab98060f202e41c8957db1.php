<?php $__env->startSection('content'); ?>

<div class="panel panel-success">
    <div class="panel-heading text-center" style="height: 30px;padding:0;">
        <strong>DETALLES DE VENTA </strong>
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

    <div class="panel-body">

        <div class="row">
            <div class="col-lg-9 col-sm-9 col-md-9 col-xs-9">
                <div class="form-group">
                    <label>CLIENTE</label>
                    <input class="form-control" type="text" value="<?php echo e($venta->cliente_venta); ?>" disabled>
                </div>
            </div>

            <div class="col-lg-3 col-sm-3 col-md-3 col-xs-12">
                <div class="form-group">
                    <label>NÚMERO DE FACTURA</label>
                    <input class="form-control" type="text" value="<?php echo e($venta->num_factura); ?>" disabled>
                </div>
            </div>

            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                <div class="form-group">
                    <label>RESPONSABLE</label>
                    <input class="form-control" type="text" value="<?php echo e($venta->responsable_venta); ?>" disabled>
                </div>
            </div>


            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                <div class="form-group">
                    <label>FECHA Y HORA DEL PEDIDO</label>
                    <input class="form-control" type="text" value="<?php echo e($venta->fecha_venta); ?>" disabled>
                </div>
            </div>

            <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                <div class="form-group">
                    <label>OBSERVACIONES</label>
                    <input class="form-control" type="text" value="<?php echo e($venta->observaciones); ?>" disabled>
                </div>
            </div>
        </div>
        <div class="row">
                <div class="panel panel-warning">

                    <div class="panel-heading text-center" style="height: 30px;padding:0;">
                        <strong>DETALLES DE LOS PRODUCTOS VENDIDOS</strong>
                    </div>

                    <div class="panel-body">
                        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                            <div class="table-responsive">
                                <table id="detalles"
                                    class="table table-striped table-bordered table-condensed table-hover">
                                    <thead style="background-color:#A9D0F5">
                                        <th>PRODUCTO</th>
                                        <th>PRECIO</th>
                                        <th>CANTIDAD</th>
                                        <th>OBSERVACIONESS</th>
                                        <th>SUBTOTAL</th>
                                    </thead>
                                    <tfoot>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th class="text-center">
                                            <h4><strong>TOTAL VENTA</strong></h4>
                                        </th>
                                        <th>
                                            <h4 id="total"><strong><?php echo e(number_format($venta->total_venta)); ?></strong></h4>
                                        </th>
                                    </tfoot>
                                    <tbody>
                                        <?php $__currentLoopData = $ventaDetalles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $det): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($det->nombre_producto); ?></td>
                                            <td><?php echo e(number_format($det->precio_venta)); ?></td>
                                            <td><?php echo e($det->cantidad); ?></td>
                                            <td><?php echo e($det->observaciones); ?></td>
                                            <td><?php echo e(number_format($det->cantidad*$det->precio_venta)); ?></td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make("crudbooster::admin_template", \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>