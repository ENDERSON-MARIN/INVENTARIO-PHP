 <?php $__env->startSection('content'); ?>

<div class="panel panel-warning">
    <div class="panel-heading text-center" style="height: 30px;padding:0;">
        <strong>EDITAR PROVEEDOR</strong>
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
        <form method="POST" action="/admin/proveedores/update/<?php echo e($proveedor->id); ?>" enctype="multipart/form-data">
            <?php echo method_field('PATCH'); ?>
            <?php echo csrf_field(); ?>

            <div class="row ">


                <div class="col-md-12">
                    <div class="form-group">
                        <label>***NOMBRES***</label>
                        <input type="text" class="form-control" name="txtNombres" id="txtNombres" value="<?php echo e($proveedor->nombres); ?>">
                    </div>
                </div>

                <div class="col-md-9">
                    <div class="form-group">
                        <label>***IDENTIFICACIÓN***</label>
                        <input type="text" class="form-control" name="txtIdentificacion" id="txtIdentificacion"  value="<?php echo e($proveedor->identificacion); ?>">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>***ESTADO***</label>
                        <select name="slEstado" id="slEstado" class="form-control selectpicker" data-live-search="true" required>
                            <?php $__currentLoopData = $estado_proveedor; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($value===$proveedor->estado): ?>
                                    <option value="<?php echo e($value); ?>" selected><?php echo e($value); ?></option>
                                <?php else: ?>
                                    <option value="<?php echo e($value); ?>"><?php echo e($value); ?></option>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for=" ">CORREO</label>
                        <input type="email" class="form-control" name="txtCorreo" id="txtCorreo"  value="<?php echo e($proveedor->correo); ?>">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for=" ">TELEFONOS</label>
                        <input type="text" class="form-control" name="txtTelefonos" id="txtTelefonos"  value="<?php echo e($proveedor->telefonos); ?>">
                    </div>
                </div>


                <div class="col-md-12">
                    <div class="form-group">
                        <label>***DIRECCION***</label>
                        <input type="text" name="txtDireccion" id="txtDireccion"  class="form-control"  value="<?php echo e($proveedor->direccion); ?>">
                    </div>
                </div>

                <div class="col-sm-12 ">
                    <div class="form-group">
                        <label for=" ">OBSERVACIONES</label>
                        <textarea class="form-control" name="txtObservaciones" id="txtObservaciones" cols="30" rows="5"><?php echo e($proveedor->observaciones); ?></textarea>
                    </div>
                </div>

                <?php if(($proveedor->imagen)==""): ?>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label >***IMAGEN***</label>
                            <input type="file" name="imagen" id="imagen" class="form-control">
                        </div>
                    </div>

                    <div class="col-md-12 text-center">
                        <div class="form-group">
                            <img src="<?php echo e(asset('imagenes/proveedores/default-empleado.png')); ?>" height="auto" width="200" class="float-right">
                        </div>
                    </div>
                <?php endif; ?>

                <?php if(($proveedor->imagen)!=""): ?>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label >***IMAGEN***</label>
                            <input type="file" name="imagen" id="imagen" class="form-control">
                        </div>
                    </div>

                    <div class="col-md-12 text-center">
                        <div class="form-group">
                            <img src="<?php echo e(asset('imagenes/proveedores/'.$proveedor->imagen)); ?>" height="auto" width="200" class="float-right">
                        </div>
                    </div>
                <?php endif; ?>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <input type="hidden" class="form-control" name="txtResponsableEdicion" id="txtResponsableEdicion" value=<?php echo e($responsable_edicion); ?>>
                    </div>
                </div>
            </div>
            <div class="panel-footer text-center ">
                <a href="/admin/proveedores" class="btn btn-danger">Volver al listado de Proveedores</a>
                <button type="submit" class="btn btn-success">Editar Proveedor</button>
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