<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Proveedor;
use App\Models\CompraDetalles;
use App\Models\Producto;

class Compra extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */

    protected $table='compras';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id',
        'proveedor_id',
        'num_factura',
        'fecha_compra',
        'responsable_creacion',
        'responsable_edicion',
        'observaciones'
    ];

    public function proveedores()
    {
        return $this->hasOne('App\Models\Proveedor');
    }


    public function detalles()
    {
        return $this->hasMany('App\Models\CompraDetalles');
    }

    public function productos()
    {
        return $this->belongsToMany('App\Models\Producto', 'compra_detalles', 'compra_id', 'producto_id');
    }
}
