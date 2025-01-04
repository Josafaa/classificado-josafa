<?php

namespace App\Requests;

class MyBaseRequest
{
    protected $validation;
    protected $request;
    protected $response;

    public function __construct()
    {
        //BUSCANDO CLASSES E INSTANCIAS JÁ EXISTENTE SERVIÇOS
        $this->validation = service('validation');
        $this->request = service('request');
        $this->response = service('response');
    }

    //Passando um grupo de validações para cada requisição
    protected function validate(string $ruleGroup, bool $respondWithRedirect = false): void
    {
        $this->validation->setRuleGroup($ruleGroup);

        if (!$this->validation->withRequest($this->request)->run()) {

            
            //Se o metodo não foi validado
            if ($respondWithRedirect) {

                $this->respondWithRedirect();
            } 

            $this->respondWithValidationErrors();
        }
    }

    private function respondWithRedirect(): void
    {
        redirect()->back()->with('danger', lang('App.danger_validations'))
            ->with('errors_modal', $this->validation->getErrors())
            ->withInput()
            ->send();
        exit;
        // Evitar o uso do 'exit', pois pode ser tratado pelo framework
    }

    private function respondWithValidationErrors(): array
    {
        $response = [
            'success' => false,
            'token' => csrf_hash(),
            'errors' => $this->validation->getErrors(),
        ];

        if (url_is('api*')) {
            unset($response['token']);
        }

        $this->response->setJSON($response)->send();
        // Evitar o uso do 'exit', pois pode ser tratado pelo framework
        exit;
    }

    public function respondWithMessage(bool $success = true, string $message = '', int $statusCode = 200): array
    {
        $response = [
            'code'      => $statusCode,
            'success'   => $success,
            'token'     => csrf_hash(),
            'message'   => $message,
        ];

        if (url_is('api*')) {
            unset($response['token']);
        }

        return $response;
    }
}
?>
