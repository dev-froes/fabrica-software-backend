<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Projeto;

class Cliente extends Model
{
    protected $fillable = ['nome', 'email', 'telefone', 'ativo'];
    public function projetos()
    {
        return $this->hasMany(Projeto::class); 
    }
}
