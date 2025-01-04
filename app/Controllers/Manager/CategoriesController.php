<?php

namespace App\Controllers\Manager;

use App\Controllers\BaseController;
use App\Requests\CategoryRequest;
use App\Entities\Category;
use App\Services\CategoryService;
use CodeIgniter\Config\Factories;



class CategoriesController extends BaseController
{
    private $categoryService;
    private $categoryRequest;

    public function __construct() 
    {
        // Instanciando o CategoryService corretamente
        $this->categoryService = Factories::class(CategoryService::class);
        $this->categoryRequest = Factories::class(CategoryRequest::class);
    }

    public function index()
    {
        $data = [
            'title' => 'Categorias'
        ];

        return view('Manager/Categories/index', $data);
    }

    public function getAllCategories() 
    {
        if (!$this->request->isAJAX()) {

            return redirect()->back();
        }

        // Chamando o serviço e retornando os dados no formato JSON
        return $this->response->setJSON(['data' => $this->categoryService->getAllCategories()]);
    }

    public function getCategoryInfo() 
    {
        if (!$this->request->isAJAX()) {

            return redirect()->back();
        }

        $category = $this->categoryService->getCategory($this->request->getGetPost('id'));

        $options = [
            'class'         => 'form-control',
            'placeholder'   => 'Escolha...',
            'selected'      => !(empty($category->parent_id)) ? $category->parent_id : ""
        ];

        $response = [
            'category'  => $category,
            'parents'   => $this->categoryService->getMultinivel('parent_id', $options)
        ];

        // Chamando o serviço e retornando os dados no formato JSON
        return $this->response->setJSON($response);
    }

    //DE CIMA PARA BAIXO ATÉ AQUI PARECE TUDO PERFEITO ESTÃO ENCURTADOS EM LINHA AZUL CLARA

    ////////////////////////////////////////////////////////////////
    //Funções interessantes COMO ATUALIZAR VINDA DO VALIDATE E DELETAR E CRIAR DO SCRIPT//
    ////////////////////////////////////////////////////////////////
    public function create() {

        //Chama a validação, se não passar por ela com token diferente nada é alterado.
        $this->categoryRequest->validateBeforeSave('category'); 
        //Mal funionamento CodeIngniter 4 token false direito Politica de Privacidade
        
        $category = new Category($this->removeSpoofingFromRequest());
    
        // Salva a categoria, utilizando o serviço
        $this->categoryService->trySaveCategory($category);
    
        // Retorna sucesso com CSRF token atualizado
        return $this->response->setJSON($this->categoryRequest->respondWhitMessage(message: 'Dados salvos com sucesso'));
    }

    public function update() {

        //Chama a validação, se não passar por ela com token diferente nada é alterado.
        $this->categoryRequest->validateBeforeSave('category'); 
        //Mal funionamento CodeIngniter 4 token false direito Politica de Privacidade

        $category = $this->categoryService->getCategory($this->request->getPost('id'));
    
        if (!$category) {
            // Caso a categoria não seja encontrada
            return $this->response->setJSON(['error' => 'Categoria não encontrada CategoriesController.php function update']);
        }
    
        // Preenche os dados da categoria com o formulário
        $category->fill($this->removeSpoofingFromRequest());
    
        // Salva a categoria, utilizando o serviço
        if (!$this->categoryService->trySaveCategory($category)) {
            return $this->response->setJSON(['error' => 'Erro ao tentar salvar a categoria  CategoriesController.php function update']);
        }
    
        // Retorna sucesso com CSRF token atualizado
        return $this->response->setJSON($this->categoryRequest->respondWhitMessage(message: 'Dados salvos com sucesso'));
    }

    public function getDropdownParents() {

        if (!$this->request->isAJAX()) {

            return redirect()->back();
        }

        $options = [
            'class'         => 'form-control',
            'placeholder'   => 'Escolha...',
            'selected'      => ""
        ];

        $response = [
            'parents'   => $this->categoryService->getMultinivel('parent_id', $options)
        ];

        // Chamando o serviço e retornando os dados no formato JSON
        return $this->response->setJSON($response);
    }


}

