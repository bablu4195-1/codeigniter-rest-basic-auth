<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use phpDocumentor\Reflection\PseudoTypes\True_;

class BlogMigration extends Migration
{
    public function up()
    {
        $this->forge->addField
        ([
            "id" => [
                "type" => "INT",
                "constraint" => 5,
                "unsigned" => TRUE,
                "auto_increment" => TRUE
            ],
            "category_id" =>[
                "type" => "INT",
                "constraint" => 5,
                "unsigned" => TRUE,
                // "null" => False
            ],
            "title" =>[
                "type" => "VARCHAR",
                "constraint" => 150,
                "null" => False
            ],
            "content" => [
                "type" => "TEXT",
                "null" => True
            ]

        ]);
        $this->forge->addPrimaryKey("id");
        $this->forge->createTable("blogs"); 
    }
    
    public function down()
    {
        $this->forge->dropTable("blogs"); 
        
    }
}
