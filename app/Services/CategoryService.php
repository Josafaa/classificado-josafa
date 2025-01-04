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
            $btnEdit = form_button([
                'data-id'   => $category->id,
                'id'        => 'updateCategoryBtn', // id do html elemente
                'class'     => 'btn btn-primary btn-sm'
            ],
            'Editar'
        );
            $btnArchive = form_button([
                'data_id'   => $category->id,
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

    public function getMultinivel(string $name, $options = [])
    {

        $array = $this->categoryModel->asArray()->orderBy('id', 'DESC')->findAll();

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
            // Aqui seria ideal usar um log ao invés de 'die', para mensagens de erro mais amigáveis
            log_message('error', 'Erro ao tentar salvar categoria: ' . $e->getMessage());
            return false;
        }
        return true; // Adiciona confirmação de sucesso
    }

}