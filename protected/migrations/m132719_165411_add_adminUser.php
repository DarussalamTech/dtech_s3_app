<?php

class m132719_165411_add_adminUser extends DTDbMigration {

    public function up() {
        $table = "user";
        $this->addColumn($table, "type", "enum ('admin','non-admin') after  email");
        $columns = array(
            "username" => "admin",
            "name" => "admin",
            "password" => md5("admin"),
            "email" => "admin@admin.com",
            "type" => "admin",
            "create_time" => date("Y-m-d h:m:s"),
            "create_user_id" => "1",
            "update_time" => date("Y-m-d h:m:s"),
            "update_user_id" => "1",
            "activity_log" => "user insterted through console",
        );
        $this->insert($table, $columns);
    }

    public function down() {
        $table = "user";

        $this->delete($table, "username='admin'");
        $this->dropColumn($table, "type");
    }

}