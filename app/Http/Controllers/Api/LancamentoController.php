<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lancamento;
use Illuminate\Http\Request;

class LancamentoController extends Controller
{
    // GET /api/lancamentos
    public function index(Request $request)
    {
        $query = Lancamento::query();

        if ($request->filled('projeto_id')) {
            $query->where('projeto_id', $request->projeto_id);
        }

        // Ex: /api/lancamentos?inicio=2026-01-01&fim=2026-01-31
        if ($request->filled('inicio') && $request->filled('fim')) {
            $query->whereBetween('data', [$request->inicio, $request->fim]);
        }

        return response()->json($query->get());
    }

    // POST /api/lancamentos
    public function store(Request $request)
    {
        $dados = $request->validate([
            'projeto_id' => 'required|exists:projetos,id',
            'colaborador' => 'required|string',
            'data' => 'required|date',
            'horas' => 'required|numeric|min:0.1',
            'tipo' => 'required|string',
            'descricao' => 'nullable|string',  
         ]);
        $lancamento = Lancamento::create($dados);

        return response()->json($lancamento, 201);
    }

    // GET /api/lancamentos/{id}

    public function show(Lancamento $lancamento)
    {
        return response()->json($lancamento);
    }

    // PUT /api/lancamentos/{id}
    public function update(Request $request, Lancamento $lancamento)
    {
        $dados = $request->validate([
            'colaborador' => 'sometimes|required|string',
            'data' => 'sometimes|required|date',
            'horas' => 'sometimes|required|numeric|min:0.01',
            'tipo' => 'sometimes|required|string',
            'descricao' => 'nullable|string',
        ]);

        $lancamento->update($dados);

        return response()->json($lancamento);
    }

    // DELETE /api/lancamentos/{id}
    public function destroy(Lancamento $lancamento)
    {
        $lancamento->delete();

        return response()->json(null,204);
    }
}
