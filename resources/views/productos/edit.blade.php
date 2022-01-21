@extends("crudbooster::admin_template")
 @section('content')

 <div class="panel panel-danger">
    <div class="panel-heading text-center" style="height: 30px;padding:0;">
        <strong>EDITAR PRODUCTO</strong>
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
        <form method="POST" action="/admin/productos/update/{{$producto->id}}" enctype="multipart/form-data">
            @method('PATCH')
            @csrf()

            <div class="row ">

                <div class="col-md-12">
                    <div class="form-group">
                        <label for="" id="slCategoria">CATEGORIA</label>
                        <select id="slCategoria" name="slCategoria" class="form-control selectpicker"
                            data-live-search="true">
                            @foreach ($categorias as $categoria)
                            @if ($categoria->id==$producto->categoria_id)
                            <option value="{{$categoria->id}}" selected>{{$categoria->nombre}}</option>
                            @else
                            <option value="">...Seleccione la categoria...</option>
                            <option value="{{$categoria->id}}">{{$categoria->nombre}}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-12 ">
                    <div class="form-group ">
                        <label>***NOMBRE***</label>
                        <input type="text" class="form-control" name="txtNombre" id="txtNombre"  value="{{$producto->nombre}}">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>***REFERENCIA***</label>
                        <input type="text" class="form-control" name="txtReferencia" id="txtReferencia" value="{{ $producto->referencia }}">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>***PESO(GRAMOS)***</label>
                        <input type="number" name="txtPeso" id="txtPeso"  step="0.001"  min="0" class="form-control" value="{{ $producto->peso }}">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>***FECHA Y HORA ELABORACIÓN***</label>
                        <input type="text" class="form-control" name="txtFechaElaboracion" id="txtFechaElaboracion" value="{{ $producto->fecha_elaboracion }}">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>***ESTADO***</label>
                        <select name="slEstado" id="slEstado" class="form-control selectpicker" data-live-search="true" required>
                            @foreach ($estado_producto as $key => $value)
                                @if ($value===$producto->estado)
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
                        <textarea class="form-control" name="txtObservaciones" id="txtObservaciones" cols="30" rows="5">{{$producto->observaciones}}</textarea>
                    </div>
                </div>

                @if (($producto->imagen)=="")
                    <div class="col-md-12">
                        <div class="form-group">
                            <label >***IMAGEN***</label>
                            <input type="file" name="foto" id="foto" class="form-control">
                        </div>
                    </div>

                    <div class="col-md-12 text-center">
                        <div class="form-group">
                            <img src="{{asset('imagenes/productos/no-image.png')}}" height="auto" width="200" class="float-right">
                        </div>
                    </div>
                @endif

                @if (($producto->imagen)!="")

                    <div class="col-md-12">
                        <div class="form-group">
                            <label >***IMAGEN***</label>
                            <input type="file" name="foto" id="foto" class="form-control">
                        </div>
                    </div>

                    <div class="col-md-12 text-center">
                        <div class="form-group">
                            <img src="{{asset('imagenes/productos/'.$producto->imagen)}}" height="auto" width="200" class="float-right">
                        </div>
                    </div>
                @endif

            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <input type="hidden" class="form-control" name="txtResponsableEdicion" id="txtResponsableEdicion" value={{ $responsable_edicion }}>
                    </div>
                </div>
            </div>
            <div class="panel-footer text-center ">
                <a href="/admin/productos" class="btn btn-danger">Volver al listado de Productos</a>
                <button type="submit" class="btn btn-success">Editar Producto</button>
            </div>
        </form>
    </div>

    @include('sweetalert::alert')

    @endsection

    @section('custom_script')

    <script>


    </script>


    @endsection

