<?php

namespace App\Controllers\Manager;

use App\Controllers\BaseController;
use App\Entities\Category;
use App\Services\CategoryService;
use CodeIgniter\Config\Factories;

class CategoriesController extends BaseController
{
    private $categoryService;

    public function __construct() 
    {
        // Instanciando o CategoryService corretamente
        $this->categoryService = Factories::class(CategoryService::class);
    }

    public function index()
    {
        $data = [
            'title' => 'Categorias'
        ];

        return view('Manager/Categories/index', $data);
    }

    public function archived()
    {
        $data = [
            'title' => 'Categorias Arquivadas'
        ];

        return view('Manager/Categories/archived', $data);
    }

    public function getAllCategories() 
    {
        if (!$this->request->isAJAX()) {
            // Redireciona de volta se a requisição não for AJAX
            return redirect()->back();
        }

        // Retorna todas as categorias no formato JSON
        return $this->response->setJSON(['data' => $this->categoryService->getAllCategories()]);
    }

    public function getAllArchivedCategories() 
    {
        if (!$this->request->isAJAX()) {
            // Redireciona de volta se a requisição não for AJAX
            return redirect()->back();
        }

        // Retorna todas as categorias arquivadas no formato JSON
        return $this->response->setJSON(['data' => $this->categoryService->getAllArchivedCategories()]);
    }

    public function getCategoryInfo() 
    {
        if (!$this->request->isAJAX()) {
            // Redireciona de volta se a requisição não for AJAX
            return redirect()->back();
        }

        // Obtém a categoria pelo ID recebido via GET/POST
        $category = $this->categoryService->getCategory($this->request->getGetPost('id'));

        // Configurações para gerar dropdown
        $options = [
            'class'         => 'form-control',
            'placeholder'   => lang('Categories.label_choose_category'),
            'selected'      => !(empty($category->parent_id)) ? $category->parent_id : ""
        ];

        // Resposta com categoria e lista de pais
        $response = [
            'category'  => $category,
            'parents'   => $this->categoryService->getMultinivel('parent_id', $options)
        ];

        return $this->response->setJSON($response);
    }

    public function create() 
    {
        // Cria uma nova entidade de categoria com os dados da requisição
        $category = new Category($this->removeSpoofingFromRequest());

        // Tenta salvar a categoria utilizando o serviço
        $this->categoryService->trySaveCategory($category);

        // Retorna mensagem de sucesso
        return $this->response->setJSON(['message' => 'Dados salvos com sucesso']);
    }

    public function update() 
    {
        // Obtém a categoria existente pelo ID recebido
        $category = $this->categoryService->getCategory($this->request->getGetPost('id'));

        // Preenche a entidade com os novos dados
        $category->fill($this->removeSpoofingFromRequest());

        // Tenta salvar a categoria com os dados atualizados
        $this->categoryService->trySaveCategory($category);

        // Retorna mensagem de sucesso
        return $this->response->setJSON(['message' => 'Dados atualizados com sucesso']);
    }

    public function archive() 
    {
    
        $this->categoryService->tryArchiveCategory($this->request->getGetPost('id'));
    
        // Retorna sucesso com CSRF token atualizado
        return $this->response->setJSON(['message' => 'Categoria arquivada com sucesso']);
    }

    public function delete() 
{
    // Captura o ID da categoria que será excluída
    $id = $this->request->getGetPost('id');
    
    if ($id !== null) {
        // Chama o serviço para deletar permanentemente a categoria
        $this->categoryService->tryDeleteCategory($id);

        // Retorna uma mensagem de sucesso
        return $this->response->setJSON(['message' => 'Categoria excluída permanentemente com sucesso']);
    } else {
        // Caso o ID não seja válido
        return $this->response->setJSON(['message' => 'ID inválido'], 400);
    }
}

    

    public function getDropdownParents() 
    {
        if (!$this->request->isAJAX()) {
            // Redireciona de volta se a requisição não for AJAX
            return redirect()->back();
        }

        // Configurações para gerar dropdown
        $options = [
            'class'         => 'form-control',
            'placeholder'   => lang('Categories.label_choose_category'),
            'selected'      => ""
        ];

        // Resposta com lista de pais
        $response = [
            'parents'   => $this->categoryService->getMultinivel('parent_id', $options)
        ];

        return $this->response->setJSON($response);
    }

    public function recover() 
    {
        // Recupera a categoria com o ID recebido
        $this->categoryService->tryRecoverCategory($this->request->getGetPost('id'));

        // Retorna mensagem de sucesso
        return $this->response->setJSON(['message' => 'Categoria recuperada com sucesso']);
    }
}