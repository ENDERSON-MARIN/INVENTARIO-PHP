 <?php $__env->startSection('content'); ?>

<div class="panel panel-success">
    <div class="panel-heading text-center" style="height: 30px;padding:0;">
        <strong>REGISTRAR NUEVO CLIENTE</strong>
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
        <form action="/admin/clientes" method="post">
            <?php echo e(csrf_field()); ?>


            <div class="row ">

                <div class="col-md-12">
                    <div class="form-group">
                        <label>***NOMBRES***</label>
                        <input type="text" class="form-control" name="txtNombres" id="txtNombres"  placeholder="Ingrese el nombre y apellido del cliente" required>
                    </div>
                </div>

                <div class="col-md-9">
                    <div class="form-group">
                        <label>***IDENTIFICACIÓN***</label>
                        <input type="text" class="form-control" name="txtIdentificacion" id="txtIdentificacion"  placeholder="Ingrese la identificacion del cliente" required>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>***ESTADO***</label>
                        <select name="slEstado" id="slEstado" class="form-control selectpicker" data-live-search="true" required>
                                <?php $__currentLoopData = $estado_cliente; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($value); ?>"><?php echo e($value); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for=" ">CORREO</label>
                        <input type="email" class="form-control" name="txtCorreo" id="txtCorreo" placeholder="Formato: marinenderson1@gmail.com(todo en minúsculas)">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for=" ">TELEFONOS</label>
                        <input type="text" class="form-control" name="txtTelefonos" id="txtTelefonos" placeholder="Formato: 3147892088(sin comas, ni puntos, ni guiones, ni espacios)">
                    </div>
                </div>


                <div class="col-md-12">
                    <div class="form-group">
                        <label>***DIRECCION***</label>
                        <input type="text" name="txtDireccion" id="txtDireccion"  class="form-control" placeholder="Ingrese la dirección del cliente">
                    </div>
                </div>

                <div class="col-sm-12 ">
                    <div class="form-group">
                        <label for=" ">OBSERVACIONES</label>
                        <textarea class="form-control" name="txtObservaciones" id="txtObservaciones" cols="30" rows="5"  placeholder="Ingrese las observaciones del cliente"></textarea>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label>IMAGEN</label>
                            <input type="file" name="imagen" id="imagen" class="form-control">
                    </div>
                </div>


            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <input type="hidden" class="form-control" name="txtResponsableCreacion" id="txtResponsableCreacion" value=<?php echo e($responsable_creacion); ?>>
                    </div>
                </div>
            </div>
            <div class="panel-footer text-center ">
                <a href="/admin/clientes" class="btn btn-danger">Volver al listado de Clientes</a>
                <button type="submit" class="btn btn-success">Registrar Nuevo Cliente</button>
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