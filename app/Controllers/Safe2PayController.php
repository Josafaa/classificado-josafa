<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;

class Safe2PayController extends BaseController
{
    public function createPixPayment()
    {
        // Configuração do payload (conteúdo de envio)
        $payload = [
            "IsSandbox" => true, // Ambiente de teste
            "Application" => "CodeIgniter Integration",
            "Vendor" => "Safe2Pay",
            "CallbackUrl" => "https://seusite.com.br/callback",
            "PaymentMethod" => "1", // Código para PIX
            "Customer" => [
                "Name" => "João Silva",
                "Identity" => "12345678909",
                "Email" => "joao.silva@email.com",
                "Phone" => "11999999999"
            ],
            "Products" => [
                [
                    "Code" => "001",
                    "Description" => "Produto Exemplo",
                    "UnitPrice" => 100.00,
                    "Quantity" => 1
                ]
            ]
        ];

        // URL da API
        $apiUrl = 'https://payment.safe2pay.com.br/v2/Payment';

        // Configuração do cabeçalho HTTP
        $headers = [
            'X-API-KEY' => '[INFORME_SEU_TOKEN]',
            'Content-Type' => 'application/json'
        ];

        // Criando a requisição HTTP
        $client = \Config\Services::curlrequest();

        try {
            $response = $client->post($apiUrl, [
                'headers' => $headers,
                'json' => $payload,
            ]);

            // Retorna a resposta da API
            return $this->response->setStatusCode(ResponseInterface::HTTP_OK)
                                  ->setJSON([
                                      'status' => 'success',
                                      'data' => json_decode($response->getBody(), true)
                                  ]);
        } catch (\Exception $e) {
            // Captura erros e retorna resposta apropriada
            return $this->response->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)
                                  ->setJSON([
                                      'status' => 'error',
                                      'message' => $e->getMessage()
                                  ]);
        }
    }
}
