<?php 

namespace App\Services;

use App\Entities\Category; 
use App\Models\CategoryModel;

class CategoryService
{
    private $categoryModel;

    public function __construct() 
    {
        // Instanciando o CategoryModel corretamente
        $this->categoryModel = new CategoryModel();  
    }

    public function getAllCategories(): array
    {
        // Buscando as categorias
        $categories = $this->categoryModel->asObject()->orderBy('id', 'DESC')->findAll();

        $data = [];

        // Montando a resposta com os dados das categorias
        foreach ($categories as $category) {
            // Montando um botão de ação (apenas um exemplo de como você pode manipular)
            $btnEdit = form_button(
                [
                    'data-id'   => $category->id,
                    'id'        => 'updateCategoryBtn', // id do html elemente
                    'class'     => 'btn btn-primary btn-sm'
                ],
                'Editar'
            );
            $btnArchive = form_button(
                [
                    'data-id'   => $category->id, // Corrigido para 'data-id'
                    'id'        => 'archiveCategoryBtn', // id do html elemente
                    'class'     => 'btn btn-info btn-sm'
                ],
                'Arquivar'
            );
            

            $data[] = [
                'id'        => $category->id,
                'name'      => $category->name,  // Certifique-se de que 'name' está correto conforme seu banco
                'slug'      => $category->slug,
                'actions'   => $btnEdit . ' ' . $btnArchive,
            ];
        }

        return $data;
    }

    public function getAllArchivedCategories(): array
    {
        // Buscando as categorias
        $categories = $this->categoryModel->asObject()->onlyDeleted()->orderBy('id', 'DESC')->findAll();

        $data = [];

        // Montando a resposta com os dados das categorias
        foreach ($categories as $category) {
            // Montando um botão de ação (apenas um exemplo de como você pode manipular)
            $btnRecover = form_button(
                [
                    'data-id'   => $category->id,
                    'id'        => 'recoverCategoryBtn', // id do html elemente
                    'class'     => 'btn btn-primary btn-sm'
                ],
                'Recuperar'
            );
            $btnDelete = form_button(
                [
                    'data-id'   => $category->id,
                    'id'        => 'deleteCategoryBtn', // id do html elemente
                    'class'     => 'btn btn-danger btn-sm'
                ],
                'Excluir'
            );

            $data[] = [
                'id'        => $category->id,
                'name'      => $category->name,  // Certifique-se de que 'name' está correto conforme seu banco
                'slug'      => $category->slug,
                'actions'   => $btnRecover . ' ' . $btnDelete,
            ];
        }

        return $data;
    }
    
    /**
     * @param integer $id
     * @param boolean $withDeleted
     * @throws Exception
     * @return null|Category
     */

    public function getCategory(int $id, bool $withDeleted = false)
    {
        $category = $this->categoryModel->withDeleted($withDeleted)->find($id);

        if (is_null($category)) {
            
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Category not found');
        }

        return $category;
    }

    public function getMultinivel(string $name, $options = [], int $exceptCategoryID = null)
    {

        $array = $this->categoryModel->getParentCategories($exceptCategoryID);

        $class_form = "";
        if (isset($options['class'])) {
            $class_form = $options['class'];
        }

        $selected = [];


        if (isset($options['selected'])) {
            $selected = is_array($options['selected']) ? $options['selected'] : [$options['selected']];
        }

        if (isset($options['placeholder'])) {
            $placeholder = [
                'id' => '',
                'name' => $options['placeholder'],
                'parent_id' => 0
            ];
            $array[] = $placeholder;
        }

        $multiple = '';
        if (isset($options['multiple'])) {
            $multiple = 'multiple';
        }

        $select = '<select class="' . $class_form . '" name="' . $name . '" ' . $multiple . '>';
        $select .= $this->getMultiLevelOptions($array, 0, [], $selected);
        $select .= '</select>';

        return $select;
    }

    public function getMultiLevelOptions(array $array, $parent_id = 0, $parents = [], $selected = [])
    {
        static $i = 0;
        if ($parent_id == 0) {
            foreach ($array as $element) {
                if (($element['parent_id'] != 0) && !in_array($element['parent_id'], $parents)) {
                    $parents[] = $element['parent_id'];
                }
            }
        }

        $menu_html = '';
        foreach ($array as $element) {
            $selected_item = '';
            if ($element['parent_id'] == $parent_id) {
                if (in_array($element['id'], $selected)) {
                    $selected_item = 'selected';
                }
                $menu_html .= '<option value="' . $element['id'] . '" ' . $selected_item . '>';
                for ($j = 0; $j < $i; $j++) {
                    $menu_html .= '&mdash; ';
                }
                $menu_html .= $element['name'] . '</option>';
                if (in_array($element['id'], $parents)) {
                    $i++;
                    $menu_html .= $this->getMultilevelOptions($array, $element['id'], $parents, $selected);
                }
            }
        }

        $i--;
        return $menu_html;
    }

    public function trySaveCategory(Category $category, bool $protect = true) 
    {
        try {
            if ($category->hasChanged()) {
                // Usando o modelo de categoria para salvar a entidade
                $this->categoryModel->protect($protect)->save($category); 
            }
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }

    public function tryArchiveCategory(int $id) 
    {
        try {
            // Obtém a categoria com base no ID
            $category = $this->getCategory($id);
    
            // Soft delete, marcando a categoria com o valor de 'deleted_at'
            $this->categoryModel->delete($category->id); // 'true' faz o Soft Delete
    
        } catch (\Exception $e) {
            // Exibe qualquer erro que ocorra
            die($e->getMessage());
        }
    }
    public function tryRecoverCategory(int $id) 
    {
        try {

            $category = $this->getCategory($id, withDeleted: true);

            $category->recover();

            $this->trySaveCategory($category, protect: false);

        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }

    public function tryDeleteCategory(int $id) 
    {
        try {
            // Obtém a categoria com base no ID, incluindo as categorias arquivadas
            $category = $this->getCategory($id, withDeleted: true);
    
            // Deleta a categoria permanentemente
            $this->categoryModel->delete($category->id, true); // 'true' garante que a exclusão é permanente
    
        } catch (\Exception $e) {
            // Exibe qualquer erro que ocorra
            die($e->getMessage());
        }
    }

}