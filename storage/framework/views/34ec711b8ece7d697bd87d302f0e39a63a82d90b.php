 <?php $__env->startSection('content'); ?>

<div class="panel panel-danger">
    <div class="panel-heading text-center" style="height: 30px;padding:0;">
        <strong>DETALLES DE PRODUCTO</strong>
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

        <?php $__currentLoopData = $producto; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

            <div class="row">
                <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">

                    <ul class="list-group">

                        <li class="list-group-item list-group-item-success"><strong>CÓDIGO DE PRODUCTO:</strong> <?php echo e($id); ?></br></li>
                        <li class="list-group-item list-group-item-success"><strong>CATEGORIA:</strong> <?php echo e($p->categoria); ?></br></li>
                        <li class="list-group-item list-group-item-success"><strong>NOMBRE:</strong> <?php echo e($p->nombre); ?></br></li>
                        <li class="list-group-item list-group-item-success"><strong>REFERENCIA:</strong> <?php echo e($p->referencia); ?></br></li>
                        <li class="list-group-item list-group-item-success"><strong>PESO:</strong> <?php echo e($p->peso); ?></br></li>
                        <li class="list-group-item list-group-item-success"><strong>FECHA Y HORA DE ELABORACION:</strong> <?php echo e($p->fecha_elaboracion); ?></br></li>
                        <li class="list-group-item list-group-item-success"><strong>STOCK-DISPONIBLE:</strong> <?php echo e($p->stock); ?> </br> </li>
                        <li class="list-group-item list-group-item-success"><strong>ESTADO DE PRODUCTO:</strong> <?php echo e($p->estado); ?> </br> </li>
                        <li class="list-group-item list-group-item-success"><strong>OBSERVACIONES:</strong> <?php echo e($p->observaciones); ?> </br> </li>
                        <li class="list-group-item list-group-item-success"><strong>CREADO POR:</strong> <?php echo e($p->creado_por); ?></br></li>
                        <li class="list-group-item list-group-item-success"><strong>FECHA Y HORA CREACIÓN:</strong> <?php echo e($p->created_at); ?></br></li>
                        <li class="list-group-item list-group-item-success"><strong>FECHA Y HORA ACTUALIZACIÓN:</strong> <?php echo e($p->updated_at); ?></br></li>
                    </ul>

                </div>
            </div>

            <div class="row text-center">

                <?php if(($p->imagen)==""): ?>
                    <div class="col-md-12 text-center">
                        <div class="form-group">
                            <strong>IMAGEN:</strong></br>
                            <img src="<?php echo e(asset('imagenes/productos/no-image.png')); ?>" height="auto" width="300" class="float-right">
                        </div>
                    </div>
                <?php endif; ?>

                <?php if(($p->imagen)!=""): ?>
                    <div class="col-md-12 text-center">
                        <div class="form-group">
                            <strong>IMAGEN:</strong></br>
                            <img src="<?php echo e(asset('imagenes/productos/'.$p->imagen)); ?>" height="auto" width="300" class="float-right">
                        </div>
                    </div>
                <?php endif; ?>

            </div>

            </br>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <div class="panel-footer text-center ">
                <a href="/admin/productos" class="btn btn-danger">Volver al listado de productos</a>
            </div>


    </div>
 </div>


    <?php $__env->stopSection(); ?>

<?php echo $__env->make("crudbooster::admin_template", \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>