@extends("crudbooster::admin_template") @section('content')

<div class="panel panel-danger">
    <div class="panel-heading text-center" style="height: 30px;padding:0;">
        <strong>DETALLES DE PRODUCTO</strong>
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

        @foreach($producto as  $p)

            <div class="row">
                <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">

                    <ul class="list-group">

                        <li class="list-group-item list-group-item-success"><strong>CÓDIGO DE PRODUCTO:</strong> {{$id}}</br></li>
                        <li class="list-group-item list-group-item-success"><strong>CATEGORIA:</strong> {{$p->categoria}}</br></li>
                        <li class="list-group-item list-group-item-success"><strong>NOMBRE:</strong> {{$p->nombre}}</br></li>
                        <li class="list-group-item list-group-item-success"><strong>REFERENCIA:</strong> {{$p->referencia}}</br></li>
                        <li class="list-group-item list-group-item-success"><strong>PESO:</strong> {{$p->peso}}</br></li>
                        <li class="list-group-item list-group-item-success"><strong>FECHA Y HORA DE ELABORACION:</strong> {{$p->fecha_elaboracion}}</br></li>
                        <li class="list-group-item list-group-item-success"><strong>STOCK-DISPONIBLE:</strong> {{$p->stock}} </br> </li>
                        <li class="list-group-item list-group-item-success"><strong>ESTADO DE PRODUCTO:</strong> {{$p->estado}} </br> </li>
                        <li class="list-group-item list-group-item-success"><strong>OBSERVACIONES:</strong> {{$p->observaciones}} </br> </li>
                        <li class="list-group-item list-group-item-success"><strong>CREADO POR:</strong> {{$p->creado_por}}</br></li>
                        <li class="list-group-item list-group-item-success"><strong>FECHA Y HORA CREACIÓN:</strong> {{$p->created_at}}</br></li>
                        <li class="list-group-item list-group-item-success"><strong>FECHA Y HORA ACTUALIZACIÓN:</strong> {{$p->updated_at}}</br></li>
                    </ul>

                </div>
            </div>

            <div class="row text-center">

                @if (($p->imagen)=="")
                    <div class="col-md-12 text-center">
                        <div class="form-group">
                            <strong>IMAGEN:</strong></br>
                            <img src="{{asset('imagenes/productos/no-image.png')}}" height="auto" width="300" class="float-right">
                        </div>
                    </div>
                @endif

                @if (($p->imagen)!="")
                    <div class="col-md-12 text-center">
                        <div class="form-group">
                            <strong>IMAGEN:</strong></br>
                            <img src="{{asset('imagenes/productos/'.$p->imagen)}}" height="auto" width="300" class="float-right">
                        </div>
                    </div>
                @endif

            </div>

            </br>

        @endforeach

            <div class="panel-footer text-center ">
                <a href="/admin/productos" class="btn btn-danger">Volver al listado de productos</a>
            </div>


    </div>
 </div>


    @endsection
