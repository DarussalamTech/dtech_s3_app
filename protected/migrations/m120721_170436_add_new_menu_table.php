<?php

class m120721_170436_add_new_menu_table extends DTDbMigration
{

    public function up()
    {
        $table = "menus";
        $columns = array(
            'id' => 'int(11) unsigned NOT NULL AUTO_INCREMENT',
            'pid' => 'int(11) unsigned DEFAULT NULL',
            'root_parent' => 'int(11) NOT NULL',
            'controller' => 'varchar(255) DEFAULT NULL',
            'action' => 'varchar(255) DEFAULT NULL',
            'default_title' => 'varchar(255) DEFAULT NULL',
            'user_title' => 'varchar(255) DEFAULT NULL',
            'is_assigned' => 'enum("Yes","No") NOT NULL',
            'weight' => 'int(11) DEFAULT NULL',
            'min_permission' => 'varchar(255) NOT NULL',
            'root_class' => 'varchar(255) DEFAULT NULL',
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
        $table = "menus";
        $this->dropTable($table);
    }

}