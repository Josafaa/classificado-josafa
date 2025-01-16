<?php

namespace App\Controllers\Manager;

use App\Controllers\BaseController;
use App\Entities\Plan;
use App\Requests\PlanRequest;
use App\Services\PlanService;
use CodeIgniter\Config\Factories;

class PlansController extends BaseController
{
    private $planService;
    private $planRequest;


    public function __construct()
    {
        $this->planService = Factories::class(PlanService::class);
        $this->planRequest = Factories::class(PlanRequest::class);
    }

    public function index()
    {
        $data = [
            'title' => 'Planos'
        ];

        return view('Manager/Plans/index');

    }

    public function archived()    {

        $data = [
            'title' => 'Planos'
        ];

        return view('Manager/Plans/archived');

    }

    public function getAllPlans() 
    {
        if (!$this->request->isAJAX()) {
            // Redireciona de volta se a requisição não for AJAX
            return redirect()->back();
        }

        // Retorna todas as categorias no formato JSON
        return $this->response->setJSON(['data' => $this->planService->getAllPlans()]);
    }

    public function getAllArchived() 
    {
        if (!$this->request->isAJAX()) {
            // Redireciona de volta se a requisição não for AJAX
            return redirect()->back();
        }

        // Retorna todas as categorias no formato JSON
        return $this->response->setJSON(['data' => $this->planService->getAllArchived()]);
    }


    public function getRecorrences() 
    {
        if (!$this->request->isAJAX()) {
            // Redireciona de volta se a requisição não for AJAX
            return redirect()->back();
        }

        // Retorna todas as categorias no formato JSON
        return $this->response->setJSON(['recorrences' => $this->planService->getRecorrences()]);
    }

    public function create() 
    {
        //$this->planRequest->validateBeforeSave('plan');


        $plan = new Plan($this->removeSpoofingFromRequest());


        $this->planService->trySavePlan($plan);

        //return $this->response->setJSON($this->planRequest->respondWithMessage(message: lang('App.succes_saved')));

        return $this->response->setJSON(['message' => 'Dados salvos com sucesso']);
    }

    public function getPlanInfo() 
    {
        if (!$this->request->isAJAX()) {
            // Redireciona de volta se a requisição não for AJAX
            return redirect()->back();
        };

        // Resposta com categoria e lista de pais
        $response = [
            'plan'  => $plan = $this->planService->getPlanByID($this->request->getGetPost('id')),
            //'parents'   => $this->planService->getMultinivel('parent_id', $options)
            'recorrences' => $this->planService->getRecorrences($plan->recorrence)
        ];

        return $this->response->setJSON($response);
    }


    public function update() 
    {
        //$this->planRequest->validateBeforeSave('plan');

        $plan = $this->planService->getPlanByID($this->request->getGetPost('id'));

        $plan->fill($this->removeSpoofingFromRequest());

        $this->planService->trySavePlan($plan);
        

        //return $this->response->setJSON($this->planRequest->respondWithMessage(message: lang('App.succes_saved')));

        return $this->response->setJSON(['message' => 'Dados salvos com sucesso']);
    }

    protected function removeSpoofingFromRequest(): array
    {
        $data = $this->request->getPost();

        // Converte 'is_highlighted' para booleano antes de salvar
        if (isset($data['is_highlighted'])) {
            $data['is_highlighted'] = (int) (bool) $data['is_highlighted'];
        }

        // Garante que recorrence está definido
        if (!isset($data['recorrence'])) {
            $data['recorrence'] = Plan::OPTION_MONTHLY; // Valor padrão, se necessário
        }

        return $data;
    }


    public function archive() 
    {

        $this->planService->tryArchivePlan($this->request->getGetPost('id'));

        return $this->response->setJSON(['message' => 'Dados Arquivados com sucesso']);
    }

    public function recover() 
    {

        $this->planService->tryRecoverPlan($this->request->getGetPost('id'));

        return $this->response->setJSON(['message' => 'Dados Recuperados com sucesso']);
    }


    public function delete() 
    {

        $this->planService->tryDeletePlan($this->request->getGetPost('id'));

        return $this->response->setJSON(['message' => 'Dados Deletados com sucesso']);
    }

    



}
