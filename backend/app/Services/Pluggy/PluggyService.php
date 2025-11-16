<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PluggyService
{
    private string $baseUrl = 'https://api.pluggy.ai';

    /**
     * Autentica na Pluggy e retorna apiKey
     */
    public function authenticate(): string
    {
        $response = Http::post($this->baseUrl . '/auth', [
            'clientId' => env('PLUGGY_CLIENT_ID'),
            'clientSecret' => env('PLUGGY_CLIENT_SECRET'),
        ]);

        if ($response->failed()) {
            throw new \Exception("Erro ao autenticar Pluggy: " . $response->body());
        }

        return $response->json('apiKey');
    }

    /**
     * Gera connectToken para o Pluggy Connect Widget
     * (Fluxo real oficial da Pluggy)
     */
    public function generateConnectToken(): string
    {
        $apiKey = $this->authenticate();

        $response = Http::withToken($apiKey)
            ->post($this->baseUrl . '/connect_token');

        if ($response->failed()) {
            throw new \Exception("Erro ao gerar connectToken: " . $response->body());
        }

        return $response->json('connectToken');
    }

    /**
     * Cria um item demo (para testes / sandbox)
     */
    public function createDemoItem()
    {
        $apiKey = $this->authenticate();

        $response = Http::withToken($apiKey)
            ->post($this->baseUrl . '/items', [
                "connectorId" => 1, // Banco demo interno da Pluggy
                "parameters" => [
                    "credential1" => "user",
                    "credential2" => "password"
                ]
            ]);

        if ($response->failed()) {
            throw new \Exception("Erro ao criar item demo: " . $response->body());
        }

        return $response->json();
    }

    /**
     * Lista contas vinculadas ao item (real ou demo)
     */
    public function getAccounts($itemId)
    {
        $apiKey = $this->authenticate();

        $response = Http::withToken($apiKey)
            ->get($this->baseUrl . '/accounts', [
                'itemId' => $itemId
            ]);

        if ($response->failed()) {
            throw new \Exception("Erro ao buscar contas: " . $response->body());
        }

        return $response->json();
    }

    /**
     * Lista transações vinculadas ao item
     */
    public function getTransactions($itemId)
    {
        $apiKey = $this->authenticate();

        $response = Http::withToken($apiKey)
            ->get($this->baseUrl . '/transactions', [
                'itemId' => $itemId,
                'pageSize' => 200,   
            ]);

        if ($response->failed()) {
            throw new \Exception("Erro ao buscar transações: " . $response->body());
        }

        return $response->json();
    }
}
