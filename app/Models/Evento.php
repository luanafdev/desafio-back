<?php
// app/Models/Evento.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Evento extends Model
{

    use HasFactory;
    protected $fillable = [
        'produtor_id',
        'nome',
        'descricao',
        'data_evento',
        'banner_url',
        'localizacao'
    ];

    public function setores()
    {
        return $this->hasMany(Setor::class);
    }
    
    public function produtor()
    {
        return $this->belongsTo(Produtor::class);
    }
}