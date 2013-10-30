<?php

class m131030_061822_addemailconf extends CDbMigration {

    public function up() {
        $table = "conf_misc";
        
        $columns = array(
            "title" => "email",
            "param" => "smtp",
            "value" => "1",
            "field_type" => "dropdown",
            
            "create_time" => date("Y-m-d h:m:s"),
            "create_user_id" => "1",
            "update_time" => date("Y-m-d h:m:s"),
            "update_user_id" => "1",
            "activity_log" => "user insterted through console",
        );
        $this->insert($table, $columns);
    }

    public function down() {
        echo "m131030_061822_addemailconf does not support migration down.\n";
        return false;
    }

    /*
      // Use safeUp/safeDown to do migration with transaction
      public function safeUp()
      {
      }

      public function safeDown()
      {
      }
     */
}