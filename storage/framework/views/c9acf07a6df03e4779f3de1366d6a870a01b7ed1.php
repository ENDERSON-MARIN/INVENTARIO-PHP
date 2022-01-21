 <?php $__env->startSection('content'); ?>

<div class="panel panel-success">
    <div class="panel-heading text-center" style="height: 30px;padding:0;">
        <strong>DETALLES DE CLIENTE</strong>
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

        <?php $__currentLoopData = $cliente; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

            <div class="row">
                <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">

                    <ul class="list-group">

                        <li class="list-group-item list-group-item-success"><strong>IDENTIFICACION:</strong> <?php echo e($c->identificacion); ?></br></li>
                        <li class="list-group-item list-group-item-success"><strong>NOMBRES:</strong> <?php echo e($c->nombres); ?></br></li>
                        <li class="list-group-item list-group-item-success"><strong>CORREO:</strong> <?php echo e($c->correo); ?></br></li>
                        <li class="list-group-item list-group-item-success"><strong>TELEFONOS:</strong> <?php echo e($c->telefonos); ?></br></li>
                        <li class="list-group-item list-group-item-success"><strong>DIRECCION:</strong> <?php echo e($c->direccion); ?> </br> </li>
                        <li class="list-group-item list-group-item-success"><strong>ESTADO:</strong> <?php echo e($c->estado); ?> </br> </li>
                        <li class="list-group-item list-group-item-success"><strong>OBSERVACIONES:</strong> <?php echo e($c->observaciones); ?> </br> </li>
                        <li class="list-group-item list-group-item-success"><strong>CREADO POR:</strong> <?php echo e($c->creado_por); ?></br></li>
                        <li class="list-group-item list-group-item-success"><strong>FECHA Y HORA CREACIÓN:</strong> <?php echo e($c->created_at); ?></br></li>
                        <li class="list-group-item list-group-item-success"><strong>FECHA Y HORA ACTUALIZACIÓN:</strong> <?php echo e($c->updated_at); ?></br></li>
                    </ul>

                </div>
            </div>

            <div class="row text-center">

                <?php if(($c->imagen)==""): ?>
                    <div class="col-md-12 text-center">
                        <div class="form-group">
                            <strong>IMAGEN:</strong></br>
                            <img src="<?php echo e(asset('imagenes/clientes/default-empleado.png')); ?>" height="auto" width="300" class="float-right">
                        </div>
                    </div>
                <?php endif; ?>

                <?php if(($c->imagen)!=""): ?>
                    <div class="col-md-12 text-center">
                        <div class="form-group">
                            <strong>IMAGEN:</strong></br>
                            <img src="<?php echo e(asset('imagenes/clientes/'.$c->imagen)); ?>" height="auto" width="300" class="float-right">
                        </div>
                    </div>
                <?php endif; ?>

            </div>

            </br>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <div class="panel-footer text-center ">
                <a href="/admin/clientes" class="btn btn-danger">Volver al listado de Clientes</a>
            </div>


    </div>
 </div>


    <?php $__env->stopSection(); ?>

<?php echo $__env->make("crudbooster::admin_template", \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>