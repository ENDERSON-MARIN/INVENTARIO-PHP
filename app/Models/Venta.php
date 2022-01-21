<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Cliente;
use App\Models\VentaDetalles;
use App\Models\Producto;


class Venta extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */

    protected $table='ventas';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id',
        'cliente_id',
        'num_factura',
        'fecha_venta',
        'responsable_creacion',
        'responsable_edicion',
        'observaciones'
    ];

    public function cliente()
    {
        return $this->hasOne('App\Models\Cliente');
    }


    public function detalles()
    {
        return $this->hasMany('App\Models\VentaDetalles');
    }

    public function productos()
    {
        return $this->belongsToMany('App\Models\Producto', 'venta_detalles', 'venta_id', 'producto_id');
    }
}
