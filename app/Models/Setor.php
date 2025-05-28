<?php
// app/Models/Evento.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setor extends Model
{
    use HasFactory;
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