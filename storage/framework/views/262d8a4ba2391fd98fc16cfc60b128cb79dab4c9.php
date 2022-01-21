 <?php $__env->startSection('content'); ?>

 <div class="panel panel-danger">
    <div class="panel-heading text-center" style="height: 30px;padding:0;">
        <strong>REGISTRAR NUEVO PRODUCTO</strong>
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
        <form action="/admin/productos" method="post" enctype="multipart/form-data">
            <?php echo e(csrf_field()); ?>


            <div class="row ">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>***CATEGORÍA***</label>
                        <select name="slCategoria" id="slCategoria" class="form-control selectpicker"
                            data-live-search="true" required>
                            <option value="">...Busque y Seleccione la Categoría del Producto...</option>
                            <?php $__currentLoopData = $categorias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoria): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($categoria->id); ?>"><?php echo e($categoria->nombre); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label>***NOMBRE***</label>
                        <input type="text" class="form-control" name="txtNombre" id="txtNombre"  placeholder="Ingrese el nombre detallado del producto" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>***REFERENCIA***</label>
                        <input type="text" class="form-control" name="txtReferencia" id="txtReferencia"  placeholder="Ingrese la Referencia  del producto" required>
                    </div>
                </div>


                <div class="col-md-6">
                    <div class="form-group">
                        <label>***PESO(GRAMOS)***</label>
                        <input type="number" name="txtPeso" id="txtPeso"  step="0.001"  min="0" class="form-control" placeholder="Peso de Producto" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>***FECHA Y HORA ELABORACIÓN***</label>
                        <input type="datetime-local" class="form-control" name="txtFechaElaboracion" id="txtFechaElaboracion" required>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>***ESTADO***</label>
                        <select name="slEstado" id="slEstado" class="form-control selectpicker" data-live-search="true" required>
                                <?php $__currentLoopData = $estado_producto; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($value); ?>"><?php echo e($value); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="">***STOCK INICIAL***</label>
                        <input type="number" name="txtStock" id="txtStock"  step="0.001" class="form-control" readonly="true" value="<?php echo e($stock_inicial); ?>">
                    </div>
                </div>


                <div class="col-sm-12 ">
                    <div class="form-group">
                        <label for=" ">OBSERVACIONES</label>
                        <textarea class="form-control" name="txtObservaciones" id="txtObservaciones" cols="30" rows="5"  placeholder="Ingrese las observaciones del producto"></textarea>
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
                <a href="/admin/productos" class="btn btn-danger">Volver al listado de Productos</a>
                <button type="submit" class="btn btn-success">Registrar Nuevo Producto</button>
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