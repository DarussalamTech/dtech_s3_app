<?php

class m131030_115550_s3Filestable extends CDbMigration {

    public function up() {

        $this->createTable('s3_files', array(
            'id' => 'INT(11)  NOT NULL AUTO_INCREMENT',
            'name' => 'varchar(200) NOT NULL',
            'hash' => 'varchar(100) NOT NULL',
            'bucket_id' => 'INT(11) NOT NULL',
            'create_time' => 'datetime NOT NULL',
            'create_user_id' => 'int(11) unsigned NOT NULL',
            'update_time' => 'datetime NOT NULL',
            'update_user_id' => 'int(11) unsigned NOT NULL',
            'activity_log' => 'text',
            'PRIMARY KEY (`id`)'
                ), 'ENGINE=InnoDB');
    }

    public function down() {
        $this->dropTable('s3_files');
    }

}