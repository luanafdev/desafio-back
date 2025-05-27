<?php
// app/Models/Evento.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setor extends Model
{
    protected $fillable = [
        'evento_id',
        'nome',
        'descricao',
    ];

    protected $table = 'setores';

    public function evento() {
        return $this->belongsTo(Evento::class);
    }

}