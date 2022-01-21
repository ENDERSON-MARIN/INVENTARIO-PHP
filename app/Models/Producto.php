<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Categoria;
use App\Models\Compras;

class Producto extends Model
{
    use SoftDeletes;

    protected $table='productos';

    protected $primaryKey= 'id';

    public $timestamps=true;

    protected $dates = ['deleted_at'];

    protected $fillable=[

    	'id',
        'categoria_id',
    	'nombre',
    	'referencia',
    	'peso',
        'stock',
        'estado',
        'fecha_elaboracion',
        'observaciones',
        'responsable_creacion',
        'responsable_edicion',
        'imagen'

    ];

    public function categoria()
    {
        return $this->belongsTo('App\Models\Categoria');
    }

    public function compras()
    {
        return $this->belongsToMany('App\Models\Compras', 'compras_detalles', 'compra_id', 'producto_id');
    }
}

