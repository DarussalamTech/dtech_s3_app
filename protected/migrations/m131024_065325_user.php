<?php

class m131024_065325_user extends CDbMigration {

    public function up() {
        $this->createTable('user', array(
            'id' => 'INT(11)  NOT NULL AUTO_INCREMENT',
            'username' => 'varchar(200) NOT NULL',
            'password' => 'varchar(200) NOT NULL',
            'name' => 'varchar(200) NOT NULL',
            'email' => 'varchar(100) NOT NULL',
            'address' => 'varchar(250) NOT NULL',
            'phone' => 'INT(11)  NOT NULL',
            'awsaccesskey' => 'varchar(200) NOT NULL',
            'awssecretkey' => 'varchar(200) NOT NULL',
            'PRIMARY KEY (`id`)'
                ), 'ENGINE=InnoDB');
    }

    public function down() {
        $this->dropTable('user');
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