<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
    public function index() {
        return view('chat');
    }

    public function responder(Request $request) {
        $mensagemUser = $request->input('mensagem'); 
        $apiKey = env('GEMINI_API_KEY');

        // URL exata do cURL, sem o "?key=" no final
        $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent';

        try {
            // Aqui está a mágica: enviamos a chave no Header 'X-goog-api-key'
            $respostaAPI = Http::withoutVerifying()->withHeaders([
                'Content-Type' => 'application/json',
                'X-goog-api-key' => $apiKey,
            ])->post($url, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $mensagemUser]
                        ]
                    ]
                ]
            ]);

            if ($respostaAPI->successful()) {
                $dados = $respostaAPI->json();
                $respostaBot = $dados['candidates'][0]['content']['parts'][0]['text'];
            } else {
                $erroDoGoogle = $respostaAPI->json();
                $mensagemDeErro = $erroDoGoogle['error']['message'] ?? 'Erro desconhecido na API';
                $respostaBot = "O Google negou o acesso. Motivo: " . $mensagemDeErro;
            }

        } catch (\Exception $e) {
            $respostaBot = "Ocorreu um erro de conexão: " . $e->getMessage();
        }

        return view('chat', [
            'resposta' => $respostaBot,
            'mensagemUser' => $mensagemUser
        ]);
    }
}