<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cliente;

class ClienteController extends Controller
{
    // GET /api/clientes?search=termo
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = Cliente::query();
        if ($search)  {
            $query->where('nome', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }

        return response()->json($query->orderBy('id', 'desc')->get());
    }

    //POST /api/clientes
    public function store(Request $request)
{
    $data = $request->validate([
        'nome' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'max:255', 'unique:clientes,email'],
        'telefone' => ['nullable', 'string', 'max:50'],
        'ativo' => ['sometimes', 'boolean'],
    ]);

    $cliente = Cliente::create($data);

    return response()->json($cliente, 201);
}

    
    public function show(Cliente $cliente)
    {
        return response()->json($cliente);
    }

    public function update(Request $request, Cliente $cliente)
    {
        $data = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:clientes,email,' . $cliente->id],
            'telefone' => ['nullable', 'string', 'max:50'],
            'ativo' => ['sometimes', 'boolean'],
        ]);

        $cliente->update($data);

        return response()->json($cliente);
    }
    public function destroy(Cliente $cliente)
    {
        $cliente->delete();

        return response()->json(null, 204);
    }
}
