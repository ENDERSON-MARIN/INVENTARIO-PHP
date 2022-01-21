<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VentaDetalles extends Model
{
    protected $table='venta_detalles';

    protected $dates = ['deleted_at'];

    protected $fillable = ['id',
     'venta_id',
     'producto_id',
     'cantidad',
     'precio_venta',
     'observaciones'
    ];
}
