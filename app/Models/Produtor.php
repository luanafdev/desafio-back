<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produtor extends Model
{
    use HasFactory;
    protected $table = 'produtores';

    protected $fillable = [
        'usuario_id',
        'nome_empresa',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function eventos()
    {
        return $this->hasMany(Evento::class);
    }   
}