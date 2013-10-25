<?php

class m130129_155820_conf_mis extends DTDbMigration
{

    public function up()
    {
        $table = "conf_misc";
        $columns = array(
            'id' => 'int(11) unsigned NOT NULL AUTO_INCREMENT',
            'title' => 'varchar(150) NOT NULL',
            'param' => 'varchar(150) NOT NULL',
            'value' => 'varchar(150) NOT NULL',
            'field_type' => 'varchar(150) NOT NULL',
            'create_time' => 'datetime NOT NULL',
            'create_user_id' => 'int(11) unsigned NOT NULL',
            'update_time' => 'datetime NOT NULL',
            'update_user_id' => 'int(11) unsigned NOT NULL',
            'activity_log' => 'text',
            'PRIMARY KEY (`id`)',
        );
        $this->createTable($table, $columns);
    }

    public function down()
    {
        $table = "conf_misc";
        $this->dropTable($table);
    }

}