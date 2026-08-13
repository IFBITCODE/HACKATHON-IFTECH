<?php

namespace App\Http\Controllers;

use App\Models\Empreendedor;
use Illuminate\Http\Request;

class PrefeituraController extends Controller
{
    /**
     * Lista todos os empreendedores que estão aguardando aprovação.
     */
    public function listarPendentes()
    {
        // Busca apenas quem tem o status 'pendente'
        $pendentes = Empreendedor::where('status', 'pendente')
                                 ->latest()
                                 ->get();

        return response()->json($pendentes);
    }

    /**
     * A Prefeitura aprova o empreendedor para receber turistas.
     */
    public function aprovarEmpreendedor($id)
    {
        $empreendedor = Empreendedor::findOrFail($id);

        // Trava de segurança: verifica se já não está aprovado
        if ($empreendedor->status === 'aprovado') {
            return response()->json([
                'message' => 'Este empreendedor já está autorizado.'
            ], 400);
        }

        // Atualiza o status para aprovado
        $empreendedor->update([
            'status' => 'aprovado',
            'motivo_rejeicao' => null // Limpa qualquer motivo de rejeição anterior
        ]);

        return response()->json([
            'message' => 'Autorização concedida! O empreendedor já pode receber turistas pelo app.',
            'empreendedor' => $empreendedor
        ]);
    }

    /**
     * A Prefeitura rejeita a solicitação e exige um motivo.
     */
    public function rejeitarEmpreendedor(Request $request, $id)
    {
        $request->validate([
            'motivo_rejeicao' => 'required|string|max:500',
        ]);

        $empreendedor = Empreendedor::findOrFail($id);

        $empreendedor->update([
            'status' => 'rejeitado',
            'motivo_rejeicao' => $request->motivo_rejeicao
        ]);

        return response()->json([
            'message' => 'Solicitação rejeitada com sucesso.',
            'empreendedor' => $empreendedor
        ]);
    }
}