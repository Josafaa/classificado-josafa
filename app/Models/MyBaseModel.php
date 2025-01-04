<?php
    namespace App\Models;

    use CodeIgniter\Model;

    class MyBaseModel extends Model {
        public function __construct() {
            parent::__construct(); //Construtor do Model
        }
        protected function escapeDataXSS(array $data) {
            return esc($data);//Proteção contra XSS, escapa os dados e de pois insere
        }
        protected function setSQLMode() {
            $this->db->simpleQuery("set session sql_mode=''"); //Proteção
        }
    }

    