<?php
namespace App\Services;

use App\Entities\Plan;
use App\Models\PlanModel;
use CodeIgniter\Config\Factories;

class PlanService
{
    private $planModel;
    private $Safe2payService;

    public function __construct()
    {
        $this->planModel = Factories::models(PlanModel::class);
        $this->Safe2payService = Factories::class(safe2payService::class);
    }

    public function getAllPlans(): array
    {
        $plans = $this->planModel->findAll();

        $data = [];

        // Montando a resposta com os dados das categorias
        foreach ($plans as $plan) {
            // Montando um botão de ação (apenas um exemplo de como você pode manipular)
            $btnEdit = form_button(
                [
                    'data-id'   => $plan->id,
                    'id'        => 'updatePlanBtn', // id do html elemente
                    'class'     => 'btn btn-primary btn-sm'
                ],
                'Editar'
            );
            $btnArchive = form_button(
                [
                    'data-id'   => $plan->id, // Corrigido para 'data-id'
                    'id'        => 'archivePlanBtn', // id do html elemente
                    'class'     => 'btn btn-info btn-sm'
                ],
                'Arquivar'
            );
            
            //Mandando para o front-end
            $data[] = [
                'code'              => $plan->plan_id,
                'name'              => $plan->name,  // Certifique-se de que 'name' está correto conforme seu banco
                'is_highlighted'    => $plan->isHighlighted(),
                'details'           => $plan->details(),
                'actions'           => $btnEdit . ' ' . $btnArchive,
            ];
        }

        return $data;
    }

    public function getAllArchived(): array
    {
        $plans = $this->planModel->onlyDeleted()->findAll();

        $data = [];

        // Montando a resposta com os dados das categorias
        foreach ($plans as $plan) {

            // Montando um botão de ação (apenas um exemplo de como você pode manipular)
            $btnRecover = form_button(
                [
                    'data-id'   => $plan->id,
                    'id'        => 'recoverPlanBtn', // id do html elemente
                    'class'     => 'btn btn-primary btn-sm'
                ],
                'Recover'
            );
            $btnDelete = form_button(
                [
                    'data-id'   => $plan->id, // Corrigido para 'data-id'
                    'id'        => 'archivePlanBtn', // id do html elemente
                    'class'     => 'btn btn-danger btn-sm'
                ],
                'Deletar'
            );
            
            //Mandando para o front-end
            $data[] = [
                'code'              => $plan->plan_id,
                'name'              => $plan->name,  // Certifique-se de que 'name' está correto conforme seu banco
                'is_highlighted'    => $plan->isHighlighted(),
                'details'           => $plan->details(),
                'actions'           => $btnRecover . ' ' . $btnDelete,
            ];
        }

        return $data;
    }

    //Acima até então tudo certo

    public function getRecorrences(string $recorrence = null): string
    {
        $options = [];
        $selected = [];
        // Opções de recorrência sem usar a função lang
        $options = [
            ''                      => 'Selecione a recorrência',
            Plan::OPTION_MONTHLY    => Plan::OPTION_MONTHLY,
            Plan::OPTION_QUARTERLY  => Plan::OPTION_QUARTERLY,
            Plan::OPTION_SEMESTER   => Plan::OPTION_SEMESTER,
            Plan::OPTION_YEARLY     => Plan::OPTION_YEARLY,
        ];
    
        // Estou criando um plano?
        if (is_null($recorrence)) {
            // Se for criar um plano, nenhuma opção está selecionada
            return form_dropdown('recorrence', $options, $selected, ['class' => 'form-control']);
        }

        $selected[] = match($recorrence){

            Plan::OPTION_MONTHLY    => Plan::OPTION_MONTHLY,
            Plan::OPTION_QUARTERLY  => Plan::OPTION_QUARTERLY,
            Plan::OPTION_SEMESTER   => Plan::OPTION_SEMESTER,
            Plan::OPTION_YEARLY     => Plan::OPTION_YEARLY,
            default                 => throw new \InvalidArgumentException("Unsupported {$recorrence}")

        };
    
        // Para edição de plano, seleciona a recorrência correta
        return form_dropdown('recorrence', $options, $recorrence, ['class' => 'form-control']);
    }

    //Provavel problema em trysave

    public function trySavePlan(Plan $plan, bool $protect = true) 
    {
        try {
            if ($plan->hasChanged()) {
                // Garante que os intervalos estão corretamente definidos
                $plan->setIntervalRepeats();

                // Salva o plano no modelo
                $this->planModel->protect($protect)->save($plan); 
            }
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }

    public function getPlanByID(int $id, bool $withDeleted = false)
    {
        $plan = $this->planModel->withDeleted($withDeleted)->find($id);

        if (is_null($plan)) {
            
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Plan not found');
        }

        return $plan;
    }

    public function tryArchivePlan(int $id) 
    {
        try {
            $plan = $this->getPlanByID($id);

            $this->planModel->delete($plan->id);
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }


    public function tryRecoverPlan(int $id) 
    {
        try {
            $plan = $this->getPlanByID($id, withDeleted: true);

            $plan->recover();

            $this->planModel->protect(false)->save($plan);
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }


    public function tryDeletePlan(int $id) 
    {
        try {
            $plan = $this->getPlanByID($id, withDeleted: true);

            /**
             * @todo deletar plano na safe2pay
             */

            $this->planModel->delete($plan->id, purge: true);
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }

    private function createOrUpdatePlanSafe2pay(Plan $plan)
    {
        //Estamos Criando um Plano?
        if (empty($plan->id)){
            //Sim criamos o plano no Safe2pay

            return $this->Safe2payService->createPlan($plan);
        }

        //Estamos atualizando
        //Contudo precisamos verificar se o nome do plano foi alterado
        if ($plan->hashChanged('name')) {
            return $this->Safe2payService->updatePlan($plan);
        }
    }

}
