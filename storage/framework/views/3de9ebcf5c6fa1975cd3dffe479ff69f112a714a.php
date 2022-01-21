 <?php $__env->startSection('content'); ?>

 <div class="panel panel-danger">
    <div class="panel-heading text-center" style="height: 30px;padding:0;">
        <strong>EDITAR PRODUCTO</strong>
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
        <form method="POST" action="/admin/productos/update/<?php echo e($producto->id); ?>" enctype="multipart/form-data">
            <?php echo method_field('PATCH'); ?>
            <?php echo csrf_field(); ?>

            <div class="row ">

                <div class="col-md-12">
                    <div class="form-group">
                        <label for="" id="slCategoria">CATEGORIA</label>
                        <select id="slCategoria" name="slCategoria" class="form-control selectpicker"
                            data-live-search="true">
                            <?php $__currentLoopData = $categorias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoria): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($categoria->id==$producto->categoria_id): ?>
                            <option value="<?php echo e($categoria->id); ?>" selected><?php echo e($categoria->nombre); ?></option>
                            <?php else: ?>
                            <option value="">...Seleccione la categoria...</option>
                            <option value="<?php echo e($categoria->id); ?>"><?php echo e($categoria->nombre); ?></option>
                            <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-12 ">
                    <div class="form-group ">
                        <label>***NOMBRE***</label>
                        <input type="text" class="form-control" name="txtNombre" id="txtNombre"  value="<?php echo e($producto->nombre); ?>">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>***REFERENCIA***</label>
                        <input type="text" class="form-control" name="txtReferencia" id="txtReferencia" value="<?php echo e($producto->referencia); ?>">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>***PESO(GRAMOS)***</label>
                        <input type="number" name="txtPeso" id="txtPeso"  step="0.001"  min="0" class="form-control" value="<?php echo e($producto->peso); ?>">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>***FECHA Y HORA ELABORACIÓN***</label>
                        <input type="text" class="form-control" name="txtFechaElaboracion" id="txtFechaElaboracion" value="<?php echo e($producto->fecha_elaboracion); ?>">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>***ESTADO***</label>
                        <select name="slEstado" id="slEstado" class="form-control selectpicker" data-live-search="true" required>
                            <?php $__currentLoopData = $estado_producto; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($value===$producto->estado): ?>
                                    <option value="<?php echo e($value); ?>" selected><?php echo e($value); ?></option>
                                <?php else: ?>
                                    <option value="<?php echo e($value); ?>"><?php echo e($value); ?></option>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>

                <div class="col-sm-12 ">
                    <div class="form-group">
                        <label for=" ">OBSERVACIONES</label>
                        <textarea class="form-control" name="txtObservaciones" id="txtObservaciones" cols="30" rows="5"><?php echo e($producto->observaciones); ?></textarea>
                    </div>
                </div>

                <?php if(($producto->imagen)==""): ?>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label >***IMAGEN***</label>
                            <input type="file" name="foto" id="foto" class="form-control">
                        </div>
                    </div>

                    <div class="col-md-12 text-center">
                        <div class="form-group">
                            <img src="<?php echo e(asset('imagenes/productos/no-image.png')); ?>" height="auto" width="200" class="float-right">
                        </div>
                    </div>
                <?php endif; ?>

                <?php if(($producto->imagen)!=""): ?>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label >***IMAGEN***</label>
                            <input type="file" name="foto" id="foto" class="form-control">
                        </div>
                    </div>

                    <div class="col-md-12 text-center">
                        <div class="form-group">
                            <img src="<?php echo e(asset('imagenes/productos/'.$producto->imagen)); ?>" height="auto" width="200" class="float-right">
                        </div>
                    </div>
                <?php endif; ?>

            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <input type="hidden" class="form-control" name="txtResponsableEdicion" id="txtResponsableEdicion" value=<?php echo e($responsable_edicion); ?>>
                    </div>
                </div>
            </div>
            <div class="panel-footer text-center ">
                <a href="/admin/productos" class="btn btn-danger">Volver al listado de Productos</a>
                <button type="submit" class="btn btn-success">Editar Producto</button>
            </div>
        </form>
    </div>

    <?php echo $__env->make('sweetalert::alert', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    <?php $__env->stopSection(); ?>

    <?php $__env->startSection('custom_script'); ?>

    <script>


    </script>


    <?php $__env->stopSection(); ?>


<?php echo $__env->make("crudbooster::admin_template", \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>