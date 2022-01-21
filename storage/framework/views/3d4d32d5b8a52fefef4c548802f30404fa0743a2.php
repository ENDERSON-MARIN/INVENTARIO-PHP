 <?php $__env->startSection('content'); ?>

<div class="panel panel-warning">
    <div class="panel-heading text-center" style="height: 30px;padding:0;">
        <strong>DETALLES DEL PROVEEDOR</strong>
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

        <?php $__currentLoopData = $proveedor; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

            <div class="row">
                <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">

                    <ul class="list-group">

                        <li class="list-group-item list-group-item-success"><strong>IDENTIFICACION:</strong> <?php echo e($pv->identificacion); ?></br></li>
                        <li class="list-group-item list-group-item-success"><strong>NOMBRES:</strong> <?php echo e($pv->nombres); ?></br></li>
                        <li class="list-group-item list-group-item-success"><strong>CORREO:</strong> <?php echo e($pv->correo); ?></br></li>
                        <li class="list-group-item list-group-item-success"><strong>TELEFONOS:</strong> <?php echo e($pv->telefonos); ?></br></li>
                        <li class="list-group-item list-group-item-success"><strong>DIRECCION:</strong> <?php echo e($pv->direccion); ?> </br> </li>
                        <li class="list-group-item list-group-item-success"><strong>ESTADO:</strong> <?php echo e($pv->estado); ?> </br> </li>
                        <li class="list-group-item list-group-item-success"><strong>OBSERVACIONES:</strong> <?php echo e($pv->observaciones); ?> </br> </li>
                        <li class="list-group-item list-group-item-success"><strong>CREADO POR:</strong> <?php echo e($pv->creado_por); ?></br></li>
                        <li class="list-group-item list-group-item-success"><strong>FECHA Y HORA CREACIÓN:</strong> <?php echo e($pv->created_at); ?></br></li>
                        <li class="list-group-item list-group-item-success"><strong>FECHA Y HORA ACTUALIZACIÓN:</strong> <?php echo e($pv->updated_at); ?></br></li>
                    </ul>

                </div>
            </div>

            <div class="row text-center">

                <?php if(($pv->imagen)==""): ?>
                    <div class="col-md-12 text-center">
                        <div class="form-group">
                            <strong>IMAGEN:</strong></br>
                            <img src="<?php echo e(asset('imagenes/proveedores/no-image.png')); ?>" height="auto" width="300" class="float-right">
                        </div>
                    </div>
                <?php endif; ?>

                <?php if(($pv->imagen)!=""): ?>
                    <div class="col-md-12 text-center">
                        <div class="form-group">
                            <strong>IMAGEN:</strong></br>
                            <img src="<?php echo e(asset('imagenes/proveedores/'.$pv->imagen)); ?>" height="auto" width="300" class="float-right">
                        </div>
                    </div>
                <?php endif; ?>

            </div>

            </br>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <div class="panel-footer text-center ">
                <a href="/admin/proveedores" class="btn btn-danger">Volver al listado de proveedores</a>
            </div>
    </div>
 </div>


    <?php $__env->stopSection(); ?>

<?php echo $__env->make("crudbooster::admin_template", \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>