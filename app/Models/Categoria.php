<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Producto;

class Categoria extends Model
{
    use SoftDeletes;

    protected $table='categorias';

    protected $primaryKey= 'id';

    public $timestamps=true;

    protected $dates = ['deleted_at'];


    protected $fillable=[

    	'id',
    	'nombre',
    	'estado',
    	'observaciones'
    ];


    public function productos()
    {
        return $this->hasMany('App\Models\Producto');
    }
}
