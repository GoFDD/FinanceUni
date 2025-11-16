<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\BankAccount;

class PluggyController extends Controller
{
    private $clientId;
    private $clientSecret;
    private $baseUrl = "https://api.pluggy.ai";

    public function __construct()
    {
        $this->clientId = config('services.pluggy.client_id');
        $this->clientSecret = config('services.pluggy.client_secret');
    }

    private function getApiKey()
    {
        $response = Http::post("{$this->baseUrl}/auth", [
            'clientId' => $this->clientId,
            'clientSecret' => $this->clientSecret,
        ]);

        if (!$response->successful()) {
            throw new \Exception("Erro ao autenticar Pluggy");
        }

        return $response->json()['apiKey'];
    }

    public function generateConnectToken(Request $request)
    {
        try {
            $apiKey = $this->getApiKey();

            $response = Http::withHeaders([
                'X-API-KEY' => $apiKey,
            ])->post("{$this->baseUrl}/connect_token", [
                'clientUserId' => "user_" . $request->user()->id,
            ]);

            return response()->json([
                'accessToken' => $response->json()['accessToken'],
            ]);

        } catch (\Exception $e) {
            Log::error("Erro ao gerar connect-token", ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function saveItem(Request $request)
    {
        try {
            $user = $request->user();
            $itemId = $request->itemId;

            $apiKey = $this->getApiKey();

            //  Pega o item
            $item = Http::withHeaders([
                'X-API-KEY' => $apiKey
            ])->get("{$this->baseUrl}/items/{$itemId}")->json();

            //  Pega as contas
            $accounts = Http::withHeaders([
                'X-API-KEY' => $apiKey
            ])->get("{$this->baseUrl}/accounts", [
                'itemId' => $itemId,
            ])->json()['results'];

            $saved = [];

            foreach ($accounts as $acc) {
                $saved[] = BankAccount::updateOrCreate(
                    [
                        'pluggy_account_id' => $acc['id'],
                        'user_id' => $user->id
                    ],
                    [
                        'pluggy_item_id' => $itemId,
                        'name' => $acc['name'],
                        'type' => $acc['type'],
                        'subtype' => $acc['subtype'] ?? null,
                        'number' => $acc['number'] ?? null,
                        'balance' => $acc['balance'] ?? 0,
                        'currency_code' => $acc['currencyCode'] ?? 'BRL',
                        'institution_name' => $item['connector']['name'] ?? 'Instituição',
                    ]
                );
            }

            return response()->json([
                "message" => "Contas salvas",
                "accounts" => $saved
            ]);

        } catch (\Exception $e) {
            Log::error("Erro salvar item", ['e' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function listUserAccounts(Request $request)
    {
        return response()->json([
            "accounts" => BankAccount::where('user_id', $request->user()->id)->get()
        ]);
    }

    public function deleteAccount(Request $request, $accountId)
    {
        BankAccount::where('user_id', $request->user()->id)
            ->where('id', $accountId)
            ->delete();

        return response()->json(["message" => "Conta removida"]);
    }
}
