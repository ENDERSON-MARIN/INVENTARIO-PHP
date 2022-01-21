@extends("crudbooster::admin_template") @section('content')

<div class="panel panel-warning">
    <div class="panel-heading text-center" style="height: 30px;padding:0;">
        <strong>DETALLES DEL PROVEEDOR</strong>
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

        @foreach($proveedor as  $pv)

            <div class="row">
                <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">

                    <ul class="list-group">

                        <li class="list-group-item list-group-item-success"><strong>IDENTIFICACION:</strong> {{$pv->identificacion}}</br></li>
                        <li class="list-group-item list-group-item-success"><strong>NOMBRES:</strong> {{$pv->nombres}}</br></li>
                        <li class="list-group-item list-group-item-success"><strong>CORREO:</strong> {{$pv->correo}}</br></li>
                        <li class="list-group-item list-group-item-success"><strong>TELEFONOS:</strong> {{$pv->telefonos}}</br></li>
                        <li class="list-group-item list-group-item-success"><strong>DIRECCION:</strong> {{$pv->direccion}} </br> </li>
                        <li class="list-group-item list-group-item-success"><strong>ESTADO:</strong> {{$pv->estado}} </br> </li>
                        <li class="list-group-item list-group-item-success"><strong>OBSERVACIONES:</strong> {{$pv->observaciones}} </br> </li>
                        <li class="list-group-item list-group-item-success"><strong>CREADO POR:</strong> {{$pv->creado_por}}</br></li>
                        <li class="list-group-item list-group-item-success"><strong>FECHA Y HORA CREACIÓN:</strong> {{$pv->created_at}}</br></li>
                        <li class="list-group-item list-group-item-success"><strong>FECHA Y HORA ACTUALIZACIÓN:</strong> {{$pv->updated_at}}</br></li>
                    </ul>

                </div>
            </div>

            <div class="row text-center">

                @if (($pv->imagen)=="")
                    <div class="col-md-12 text-center">
                        <div class="form-group">
                            <strong>IMAGEN:</strong></br>
                            <img src="{{asset('imagenes/proveedores/no-image.png')}}" height="auto" width="300" class="float-right">
                        </div>
                    </div>
                @endif

                @if (($pv->imagen)!="")
                    <div class="col-md-12 text-center">
                        <div class="form-group">
                            <strong>IMAGEN:</strong></br>
                            <img src="{{asset('imagenes/proveedores/'.$pv->imagen)}}" height="auto" width="300" class="float-right">
                        </div>
                    </div>
                @endif

            </div>

            </br>

        @endforeach

            <div class="panel-footer text-center ">
                <a href="/admin/proveedores" class="btn btn-danger">Volver al listado de proveedores</a>
            </div>
    </div>
 </div>


    @endsection
