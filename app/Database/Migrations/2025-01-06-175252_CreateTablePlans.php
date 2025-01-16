<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTablePlans extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 12,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'plan_id' => [
                'type'           => 'INT',
                'constraint'     => 12,
                'null'           => true,
            ],
            'nome' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'recorence' => [
                'type'       => 'ENUM',
                'constraint' => ['monthly', 'quarterly', 'semester', 'yearly'],
            ],
            'adverts' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'description' => [
                'type'       => 'TEXT',
            ],
            'value' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'is_highlighted' => [
                'type'       => 'BOOLEAN',
                'default'    => false,
                'null'       => false,
            ],
            'created_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
                'default'    => null,
            ],
            'updated_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
                'default'    => null,
            ],
            'deleted_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
                'default'    => null,
            ],
        ]);

        // Define a chave primária
        $this->forge->addKey('id', true);

        // Corrigindo o nome do índice para 'nome' em vez de 'name'
        $this->forge->addKey('nome');

        $this->forge->createTable('plans');
    }

    public function down()
    {
        $this->forge->dropTable('plans');
    }
}