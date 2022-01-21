<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Compras;

class CompraDetalles extends Model
{
      /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */

    protected $table='compra_detalles';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id',
        'compra_id',
        'material_id',
        'cantidad',
        'precio_compra',
        'observaciones'
    ];


    public function compra()
    {
        return $this->belongsTo('App\Models\Compras');
    }
}
