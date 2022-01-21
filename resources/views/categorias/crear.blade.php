@extends("crudbooster::admin_template")
 @section('content')

<div class="panel panel-danger">
    <div class="panel-heading text-center" style="height: 30px;padding:0;">
        <strong>REGISTRAR NUEVA CATEGORIA</strong>
    </div>
    @if ($errors->any())
    <div class="alert alert-danger" role="alert">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>

            @endforeach
        </ul>
    </div>
    @endif

    <div class="panel-body">
        <form action="/admin/categorias" method="post">
            {{ csrf_field() }}

            <div class="row ">
                <div class="col-md-8 ">
                    <div class="form-group ">
                        <label>***NOMBRE CATEGORÍA***</label>
                        <input type="text" class="form-control" name="txtNombre" id="txtNombre"  placeholder="Ingrese el nombre de la Categoría" required>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>***ESTADO CATEGORÍA***</label>
                        <select name="slEstado" id="slEstado" class="form-control selectpicker" data-live-search="true" required>
                                @foreach ($estado_categoria as $key => $value)
                                    <option value="{{$value}}">{{$value}}</option>
                                @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-sm-12 ">
                    <div class="form-group">
                        <label for=" ">OBSERVACIONES</label>
                        <textarea class="form-control" name="txtObservaciones" id="txtObservaciones" cols="30" rows="5"  placeholder="Ingrese las observaciones de la herramienta"></textarea>
                    </div>
                </div>
            </div>

            <div class="panel-footer text-center ">
                <a href="/admin/categorias" class="btn btn-danger">Volver al listado de Categorías</a>
                <button type="submit" class="btn btn-success">Registrar Nueva Categoría</button>
            </div>
        </form>
    </div>

    @include('sweetalert::alert')

    @endsection

    @section('custom_script')

    <script>


    </script>


    @endsection

