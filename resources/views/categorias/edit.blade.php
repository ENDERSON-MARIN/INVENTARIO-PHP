@extends("crudbooster::admin_template")
 @section('content')

 <div class="panel panel-danger">
    <div class="panel-heading text-center" style="height: 30px;padding:0;">
        <strong>EDITAR CATEGORIA</strong>
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

    <div class="panel-body align-content-center">
        <form method="POST" action="/admin/categorias/update/{{$categoria->id}}">
            @method('PATCH')
            @csrf()

            <div class="row ">
                <div class="col-md-8 ">
                    <div class="form-group ">
                        <label>***NOMBRE CATEGORÍA***</label>
                        <input type="text" class="form-control" name="txtNombre" id="txtNombre"  value="{{ $categoria->nombre }}">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>***ESTADO DE CATEGORÍA***</label>
                        <select name="slEstado" id="slEstado" class="form-control selectpicker" data-live-search="true" required>
                            @foreach ($estado_categoria as $key => $value)
                                @if ($value===$categoria->estado)
                                    <option value="{{$value}}" selected>{{$value}}</option>
                                @else
                                    <option value="{{$value}}">{{$value}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-sm-12 ">
                    <div class="form-group">
                        <label for=" ">OBSERVACIONES</label>
                        <textarea class="form-control" name="txtObservaciones" id="txtObservaciones" cols="30" rows="5">{{ $categoria->observaciones }}</textarea>
                    </div>
                </div>
            </div>

            <div class="panel-footer text-center ">
                <a href="/admin/categorias" class="btn btn-danger">Volver al listado de Categorías</a>
                <button type="submit" class="btn btn-success">Editar Categoría</button>
            </div>
        </form>
    </div>

    @include('sweetalert::alert')

    @endsection

    @section('custom_script')

    <script>


    </script>


    @endsection

