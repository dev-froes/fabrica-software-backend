<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Cliente;

class Projeto extends Model
{
    protected $fillable = [
        'cliente_id',
        'nome',
        'descricao',
        'data_inicio',
        'data_fim',
        'valor_contrato',
        'custo_hora_base',
        'status'
    ];
    public function cliente()
    {
        return $this->belongsTo(Cliente::class); //um projeto pertence a um cliente
    }

    public function lancamentos()
    {
        return $this->hasMany(Lancamento::class); //um projeto pode ter muitos lançamentos
    }
}
