<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proveedor extends Model
{
        use SoftDeletes;

        protected $table='proveedores';

        protected $primaryKey= 'id';

        public $timestamps=true;

        protected $dates = ['deleted_at'];

        protected $fillable=[

            'id',
            'identificacion',
            'nombres',
            'referencia',
            'estado',
            'correo',
            'telefonos',
            'direccion',
            'observaciones',
            'responsable_creacion',
            'responsable_edicion',
            'imagen'

        ];
}
