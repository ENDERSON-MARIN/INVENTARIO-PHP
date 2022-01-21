@extends("crudbooster::admin_template") @section('content')

<div class="panel panel-success">
    <div class="panel-heading text-center" style="height: 30px;padding:0;">
        <strong>DETALLES DE CLIENTE</strong>
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

        @foreach($cliente as  $c)

            <div class="row">
                <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">

                    <ul class="list-group">

                        <li class="list-group-item list-group-item-success"><strong>IDENTIFICACION:</strong> {{$c->identificacion}}</br></li>
                        <li class="list-group-item list-group-item-success"><strong>NOMBRES:</strong> {{$c->nombres}}</br></li>
                        <li class="list-group-item list-group-item-success"><strong>CORREO:</strong> {{$c->correo}}</br></li>
                        <li class="list-group-item list-group-item-success"><strong>TELEFONOS:</strong> {{$c->telefonos}}</br></li>
                        <li class="list-group-item list-group-item-success"><strong>DIRECCION:</strong> {{$c->direccion}} </br> </li>
                        <li class="list-group-item list-group-item-success"><strong>ESTADO:</strong> {{$c->estado}} </br> </li>
                        <li class="list-group-item list-group-item-success"><strong>OBSERVACIONES:</strong> {{$c->observaciones}} </br> </li>
                        <li class="list-group-item list-group-item-success"><strong>CREADO POR:</strong> {{$c->creado_por}}</br></li>
                        <li class="list-group-item list-group-item-success"><strong>FECHA Y HORA CREACIÓN:</strong> {{$c->created_at}}</br></li>
                        <li class="list-group-item list-group-item-success"><strong>FECHA Y HORA ACTUALIZACIÓN:</strong> {{$c->updated_at}}</br></li>
                    </ul>

                </div>
            </div>

            <div class="row text-center">

                @if (($c->imagen)=="")
                    <div class="col-md-12 text-center">
                        <div class="form-group">
                            <strong>IMAGEN:</strong></br>
                            <img src="{{asset('imagenes/clientes/default-empleado.png')}}" height="auto" width="300" class="float-right">
                        </div>
                    </div>
                @endif

                @if (($c->imagen)!="")
                    <div class="col-md-12 text-center">
                        <div class="form-group">
                            <strong>IMAGEN:</strong></br>
                            <img src="{{asset('imagenes/clientes/'.$c->imagen)}}" height="auto" width="300" class="float-right">
                        </div>
                    </div>
                @endif

            </div>

            </br>

        @endforeach

            <div class="panel-footer text-center ">
                <a href="/admin/clientes" class="btn btn-danger">Volver al listado de Clientes</a>
            </div>


    </div>
 </div>


    @endsection
