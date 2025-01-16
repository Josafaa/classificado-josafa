<?php

namespace App\Services;

use App\Entities\Plan;
use Exception;

class Safe2payService
{
    public const API_URL = 'https://services.safe2pay.com.br/Recurrence/V1/Plans';

    private $options;

    public function __construct()
    {
        // Configurações do Safe2Pay
        $this->options = [
            'client_id'     => env('SAFE2PAY_CLIENT_ID'),
            'client_secret' => env('SAFE2PAY_CLIENT_SECRET'),
            'sandbox'       => env('SAFE2PAY_SANDBOX'),
            'timeout'       => env('SAFE2PAY_TIMEOUT'),
        ];
    }

    public function createPlan(Plan $plan)
    {
        try {
            // Configurações básicas
            $apiKey = $this->options['client_id']; // Token da API
            $url = self::API_URL;

            // Montar payload
            $payload = [
                'PlanFrequence' => $this->setIntervalRepeats($plan->recorence),
                'Name'          => $plan->name,
                'Amount'        => $plan->value,
                'Description'   => $plan->details(),
            ];

            // Headers da requisição
            $headers = [
                "X-API-KEY: {$apiKey}",
                'Content-Type: application/json',
            ];

            // Enviar requisição para API
            $response = $this->makeRequest($url, $headers, $payload);

            // Tratar resposta
            return $response;
        } catch (Exception $e) {
            // Log do erro e tratamento
            throw new Exception('Erro ao criar plano no Safe2Pay: ' . $e->getMessage());
        }
    }

    private function makeRequest(string $url, array $headers, array $payload)
    {
        // Configurar contexto HTTP
        $opts = [
            'http' => [
                'method'  => 'POST',
                'header'  => implode("\r\n", $headers),
                'content' => json_encode($payload),
                'timeout' => $this->options['timeout'],
            ],
        ];

        $context = stream_context_create($opts);
        $result = file_get_contents($url, false, $context);

        if ($result === false) {
            throw new Exception('Erro ao conectar à API Safe2Pay');
        }

        return json_decode($result, true); // Decodifica a resposta JSON
    }

    public function setIntervalRepeats(string $recorence): string
    {
        return match ($recorence) {
            Plan::OPTION_MONTHLY    => Plan::OPTION_MONTHLY,
            Plan::OPTION_QUARTERLY  => Plan::OPTION_QUARTERLY,
            Plan::OPTION_SEMESTER   => Plan::OPTION_SEMESTER,
            Plan::OPTION_YEARLY     => Plan::OPTION_YEARLY,
            default => throw new \InvalidArgumentException("Recorrência não suportada: {$recorence}"),
        };
    }
}


