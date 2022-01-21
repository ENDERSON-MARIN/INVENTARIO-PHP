@extends("crudbooster::admin_template")
 @section('content')

<div class="panel panel-warning">
    <div class="panel-heading text-center" style="height: 30px;padding:0;">
        <strong>EDITAR PROVEEDOR</strong>
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
        <form method="POST" action="/admin/proveedores/update/{{$proveedor->id}}" enctype="multipart/form-data">
            @method('PATCH')
            @csrf()

            <div class="row ">


                <div class="col-md-12">
                    <div class="form-group">
                        <label>***NOMBRES***</label>
                        <input type="text" class="form-control" name="txtNombres" id="txtNombres" value="{{ $proveedor->nombres }}">
                    </div>
                </div>

                <div class="col-md-9">
                    <div class="form-group">
                        <label>***IDENTIFICACIÓN***</label>
                        <input type="text" class="form-control" name="txtIdentificacion" id="txtIdentificacion"  value="{{ $proveedor->identificacion }}">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>***ESTADO***</label>
                        <select name="slEstado" id="slEstado" class="form-control selectpicker" data-live-search="true" required>
                            @foreach ($estado_proveedor as $key => $value)
                                @if ($value===$proveedor->estado)
                                    <option value="{{$value}}" selected>{{$value}}</option>
                                @else
                                    <option value="{{$value}}">{{$value}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for=" ">CORREO</label>
                        <input type="email" class="form-control" name="txtCorreo" id="txtCorreo"  value="{{ $proveedor->correo }}">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for=" ">TELEFONOS</label>
                        <input type="text" class="form-control" name="txtTelefonos" id="txtTelefonos"  value="{{ $proveedor->telefonos }}">
                    </div>
                </div>


                <div class="col-md-12">
                    <div class="form-group">
                        <label>***DIRECCION***</label>
                        <input type="text" name="txtDireccion" id="txtDireccion"  class="form-control"  value="{{ $proveedor->direccion }}">
                    </div>
                </div>

                <div class="col-sm-12 ">
                    <div class="form-group">
                        <label for=" ">OBSERVACIONES</label>
                        <textarea class="form-control" name="txtObservaciones" id="txtObservaciones" cols="30" rows="5">{{ $proveedor->observaciones }}</textarea>
                    </div>
                </div>

                @if (($proveedor->imagen)=="")
                    <div class="col-md-12">
                        <div class="form-group">
                            <label >***IMAGEN***</label>
                            <input type="file" name="imagen" id="imagen" class="form-control">
                        </div>
                    </div>

                    <div class="col-md-12 text-center">
                        <div class="form-group">
                            <img src="{{asset('imagenes/proveedores/default-empleado.png')}}" height="auto" width="200" class="float-right">
                        </div>
                    </div>
                @endif

                @if (($proveedor->imagen)!="")

                    <div class="col-md-12">
                        <div class="form-group">
                            <label >***IMAGEN***</label>
                            <input type="file" name="imagen" id="imagen" class="form-control">
                        </div>
                    </div>

                    <div class="col-md-12 text-center">
                        <div class="form-group">
                            <img src="{{asset('imagenes/proveedores/'.$proveedor->imagen)}}" height="auto" width="200" class="float-right">
                        </div>
                    </div>
                @endif

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <input type="hidden" class="form-control" name="txtResponsableEdicion" id="txtResponsableEdicion" value={{ $responsable_edicion }}>
                    </div>
                </div>
            </div>
            <div class="panel-footer text-center ">
                <a href="/admin/proveedores" class="btn btn-danger">Volver al listado de Proveedores</a>
                <button type="submit" class="btn btn-success">Editar Proveedor</button>
            </div>
        </form>
    </div>

    @include('sweetalert::alert')

    @endsection

    @section('custom_script')

    <script>


    </script>


    @endsection

