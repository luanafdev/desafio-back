<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lote extends Model
{

    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'setor_id',
        'nome',
        'preco',
        'quantidade',
        'data_inicio',
        'data_fim',
        'descricao',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lotes';

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'data_inicio' => 'datetime',
        'data_fim' => 'datetime',
        'preco' => 'decimal:2', // Cast 'preco' to a decimal with 2 places for consistency
    ];

    /**
     * Get the setor that owns the lote.
     */
    public function setor()
    {
        return $this->belongsTo(Setor::class);
    }
}