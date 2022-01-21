@extends("crudbooster::admin_template") @section('content')

<div class="panel panel-danger">
    <div class="panel-heading text-center" style="height: 30px;padding:0;">
        <strong>DETALLES DE CATEGORIA</strong>
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

            <div class="row">
                <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">

                    <ul class="list-group">

                        <li class="list-group-item list-group-item-success"><strong>ID DE CATEGORIA:</strong> {{$id}}</br></li>
                        <li class="list-group-item list-group-item-success"><strong>NOMBRE CATEGORÍA:</strong> {{$categoria->nombre}}</br></li>
                        <li class="list-group-item list-group-item-success"><strong>ESTADO DE CATEGORÍA:</strong> {{$categoria->estado}} </br> </li>
                        <li class="list-group-item list-group-item-success"><strong>OBSERVACIONES DE CATEGORÍA:</strong> {{$categoria->observaciones}} </br> </li>
                        <li class="list-group-item list-group-item-success"><strong>FECHA Y HORA CREACIÓN:</strong> {{$categoria->created_at}}</br></li>
                        <li class="list-group-item list-group-item-success"><strong>FECHA Y HORA ACTUALIZACIÓN:</strong> {{$categoria->updated_at}}</br></li>

                    </ul>

                </div>
            </div>

            <div class="panel-footer text-center ">
                <a href="/admin/categorias" class="btn btn-danger">Volver al listado de Categorías</a>
            </div>


    </div>
 </div>

@endsection
