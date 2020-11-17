<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mascota extends Model {
    
    // protected $table = 'my_flights';
    // protected $primaryKey = 'flight_id';

    // public $timestamps = false;

    // const CREATED_AT = 'creation_date';
    // const UPDATED_AT = 'last_update';
    public function cliente()
    {
        return $this->hasOne(User::class,'cliente_id');
    }
    public function tipoMascota()
    {
        return $this->belongsTo(Tipo_Mascota::class, 'tipo_mascota_id');
    }
}
