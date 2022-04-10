<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CategoryMigration extends Migration
{
    public function up()
    {
        $this->forge->addField([
            "id" => [
                "type" => "INT",
                "constraint" => 5,
                "unsigned" => TRUE,
                "auto_increment" => TRUE
            ],
            "name" =>[
                "type" => "VARCHAR",
                "constraint" => 150,
                "null" => False
            ],
            "status" =>[
                "type" => "ENUM",
                "constraint" => ["1", "0"],
                "default" => "1"
            ]
        ]);
        $this->forge->addPrimaryKey("id");
        $this->forge->createTable("categories");
    }

    public function down()
    {
        $this->forge->dropTable("categories");
    }
}
