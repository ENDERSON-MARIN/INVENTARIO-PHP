 <?php $__env->startSection('content'); ?>

 <div class="panel panel-danger">
    <div class="panel-heading text-center" style="height: 30px;padding:0;">
        <strong>EDITAR CATEGORIA</strong>
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
        <form method="POST" action="/admin/categorias/update/<?php echo e($categoria->id); ?>">
            <?php echo method_field('PATCH'); ?>
            <?php echo csrf_field(); ?>

            <div class="row ">
                <div class="col-md-8 ">
                    <div class="form-group ">
                        <label>***NOMBRE CATEGORÍA***</label>
                        <input type="text" class="form-control" name="txtNombre" id="txtNombre"  value="<?php echo e($categoria->nombre); ?>">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>***ESTADO DE CATEGORÍA***</label>
                        <select name="slEstado" id="slEstado" class="form-control selectpicker" data-live-search="true" required>
                            <?php $__currentLoopData = $estado_categoria; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($value===$categoria->estado): ?>
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
                        <textarea class="form-control" name="txtObservaciones" id="txtObservaciones" cols="30" rows="5"><?php echo e($categoria->observaciones); ?></textarea>
                    </div>
                </div>
            </div>

            <div class="panel-footer text-center ">
                <a href="/admin/categorias" class="btn btn-danger">Volver al listado de Categorías</a>
                <button type="submit" class="btn btn-success">Editar Categoría</button>
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