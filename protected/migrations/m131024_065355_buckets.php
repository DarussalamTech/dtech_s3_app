<?php

class m131024_065355_buckets extends CDbMigration {

    public function up() {

        $this->createTable('buckets', array(
            'name' => 'varchar(200) NOT NULL',
            'uid' => 'INT(11)  NOT NULL',
        ),'ENGINE=InnoDB');
    }

    public function down() {
        $this->dropTable('buckets');
    }

 
}