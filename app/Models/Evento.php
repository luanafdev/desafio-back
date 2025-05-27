<?php
// app/Models/Evento.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    protected $fillable = [
        'produtor_id',
        'nome',
        'descricao',
        'data_evento',
        'banner_url',
        'localizacao'
    ];

    public function produtor()
    {
        return $this->belongsTo(Produtor::class);
    }
}