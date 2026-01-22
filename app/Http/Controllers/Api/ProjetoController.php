<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\Projeto;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProjetoController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $clienteId = $request->query('cliente_id');

        $query = Projeto::query();

        if ($clienteId) {
            $query-where('cliente_id', $clienteId);
        }

        if ($search) {
            $query-where('nome', 'like', "%{$search}%");
        }

        return response()->json($query->orderBy('id', 'desc')->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'cliente_id' => ['required', 'integer', 'exists:clientes,id'],
            'nome' => ['required', 'string', 'max:255'],
            'descricao' => ['nullable', 'string'],
            'data_inicio' => ['required', 'date'],
            'data_fim' => ['nullable', 'date', 'after_or_equal:data_inicio'],
            'valor_contrato' => ['required', 'numeric', 'gt:0'],
            'custo_hora_base' => ['required', 'numeric', 'gt:0'],
            'status' => ['required', Rule::in(['planejado', 'em_andamento', 'pausado', 'finalizado'])],

        ]);

        $projeto = Projeto::create($data);

        return response()->json($projeto, 201);
    }

    public function show(Projeto $projeto)
    {
        return response()->json($projeto);
    }

    public function update(Request $request, Projeto $projeto)
    {
        $data = $request->validate([
            'cliente_id' => ['required', 'integer', 'exists:clientes,id'],
            'nome' => ['required', 'string', 'max:255'],
            'descricao' => ['nullable', 'string'],
            'data_inicio' => ['required', 'date'],
            'data_fim' => ['nullable', 'date', 'after_or_equal:data_inicio'],
            'valor_contrato' => ['required', 'numeric', 'gt:0'],
            'custo_hora_base' => ['required', 'numeric', 'gt:0'],
            'status' => ['required', Rule::in(['planejado', 'em_andamento', 'pausado', 'finalizado'])],
        ]);

        $projeto->update($data);

        return response()->json($projeto);
    }

    public function destroy(Projeto $projeto)
    {
        $projeto->delete();

        return response()->json(null, 204);
    }


    public function dashboard(Request $request, Projeto $projeto)
    {
        $data = $request->validate([
            'inicio' => ['required', 'date'],
            'fim' => ['required', 'date', 'after_or_equal:inicio'],
        ]);

        $inicio = $data['inicio'];
        $fim = $data['fim'];

        $lancamentos = $projeto->lancamentos()
            ->whereBetween('data', [$inicio, $fim])
            ->get();

        $horasTotais = (float) $lancamentos->sum('horas');
        $custoHoraBase = (float) $projeto->custo_hora_base;
        $receita = (float) $projeto->valor_contrato;

        $custoTotal = $horasTotais * $custoHoraBase;
        $margem = $receita - $custoTotal;
        $margemPercentual = $receita > 0 ? ($margem / $receita) * 100 : 0;
        $breakEvenHoras = $custoHoraBase > 0 ? ($receita / $custoHoraBase) : 0;


        $porTipo = $lancamentos
            ->groupBy('tipo')
            ->map(function ($items) use ($custoHoraBase) {
                $horas = (float) $items->sum('horas');
                return [
                    'horas' => $horas,
                    'custo' => $horas * $custoHoraBase,
                ];
            });

        return response()->json([
            'projeto' => [
                'id' => $projeto->id,
                'nome' => $projeto->nome,
                'valor_contrato' => $receita,
                'custo_hora_base' => $custoHoraBase,
                'status' => $projeto->status,
            ],
            'periodo' => [
                'inicio' => $inicio,
                'fim' => $fim,
            ],
            'metricas' => [
                'horas_totais' => $horasTotais,
                'custo_total' => $custoTotal,
                'receita' => $receita,
                'margem_bruta' => $margem,
                'margem_bruta_percentual' => $margemPercentual,
                'break_even_horas' => $breakEvenHoras,
            ],
            'resumo_por_tipo' => $porTipo,
        ]);
    }
}
