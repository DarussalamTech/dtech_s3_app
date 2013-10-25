<?php

class m131024_065355_buckets extends DTDbMigration {

    public function up() {

        $this->createTable('buckets', array(
            'id' => 'INT(11)  NOT NULL AUTO_INCREMENT',
            'name' => 'varchar(200) NOT NULL',
            'create_time' => 'datetime NOT NULL',
            'create_user_id' => 'int(11) unsigned NOT NULL',
            'update_time' => 'datetime NOT NULL',
            'update_user_id' => 'int(11) unsigned NOT NULL',
            'activity_log' => 'text',
            'PRIMARY KEY (`id`)'
                ), 'ENGINE=InnoDB');
    }

    public function down() {
        $this->dropTable('buckets');
    }

}