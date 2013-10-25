<?php

/*
  ###############################################
  ####                                       ####
  ####    @author : Ali Abbas                ####
  ####    Date   : 14 Mar,2012               ####
  ####    Updated: 19 mar,2012               ####
  ####                                       ####
  ###############################################

 */

/**
 * Class extended for used to handle such operations 
 * that are not the part of db some camel case issues 
 * in ubountu and windows 
 * 
 */
class DTDbMigration extends CDbMigration {

    /**
     * This function used to get the tables array from
     * db key of every array element in 
     * lowercase from you find acutal table name 
     * and otehr scanrio to check whether
     * table is a part of db or not
     * 1st scanario : array["subcontractor"]=>"abc";
     * 2nd scnario  : in_array("a",$array)
     *                {
     *                      //code action
     *                }
     * @return type 
     */
    public function getTables() {
        $connection = Yii::app()->db;
        $dbarr = Yii::app()->db->connectionString;
        $dbex = explode(";", $dbarr);
        $dbname = $dbex[1];
        $dbnameex = explode("=", $dbname);
        $dbname = $dbnameex[1];

        $sql = 'Show TABLES ';

        $parents = $connection->createCommand($sql)->queryAll();
        $array = array();

        foreach ($parents as $data) {
            $array[strtolower($data['Tables_in_' . $dbname . ''])] = $data['Tables_in_' . $dbname . ''];
        }
        return $array;
    }

    /**
     * get db connection 
     */
    public function getConnection() {
        $connection = Yii::app()->db;
        return $connection;
    }

    /**
     *  getDb Name;
     */
    public function getDBName() {
        $dbarr = Yii::app()->db->connectionString;
        $dbex = explode(";", $dbarr);
        $dbname = $dbex[1];
        $dbnameex = explode("=", $dbname);
        $dbname = $dbnameex[1];
        return $dbname;
    }

    /**
     * This function is extended 
     * for to handle such columns that already exist is tables
     * if in_array($columnName,$colsArray)
     * {
     * 
     * }
     * @param type $table
     * @return type 
     */
    public function getcolumns($table) {
        $connection = Yii::app()->db;

        $sql = "SHOW columns FROM " . $this->getDBName() . "." . $table;
        $command = $connection->createCommand($sql);
        $rows = $command->queryAll();
        $fields = array();
        foreach ($rows as $row) {
            $fields[strtolower($row['Field'])] = $row['Field'];
        }
        return $fields;
    }

    /**
     * Builds and executes a SQL statement for creating a new DB table.
     *
     * The columns in the new  table should be specified as name-definition pairs (e.g. 'name'=>'string'),
     * where name stands for a column name which will be properly quoted by the method, and definition
     * stands for the column type which can contain an abstract DB type.
     * The {@link getColumnType} method will be invoked to convert any abstract type into a physical one.
     *
     * If a column is specified with definition only (e.g. 'PRIMARY KEY (name, type)'), it will be directly
     * inserted into the generated SQL.
     *
     * @param string $table the name of the table to be created. The name will be properly quoted by the method.
     * @param array $columns the columns (name=>definition) in the new table.
     * @param string $options additional SQL fragment that will be appended to the generated SQL.
     */
    public function createTable($table, $columns, $options = null) {
        /**
         * for hardcoded to handle real relationships
         */
        $options = "ENGINE=InnoDB";
        $tablesArr = $this->getTables();
        if (!in_array($table, $tablesArr)) {
            parent::createTable($table, $columns, $options);
        } else {
            return true;
        }
    }

    /**
     * This function is override 
     * for to check that columns that already exist is tables.
     * @param type $table
     * @param type $columns
     * @return type 
     */
    public function alterColumn($table, $columns, $type) {
        $tablesArr = $this->getTables();
        if (!empty($tablesArr[$table])) {
            $table = $tablesArr[$table];
            parent::alterColumn($table, $columns, $type);
        } else {
            return true;
        }
    }

    /**
     * Builds and executes a SQL statement for adding a new DB column.
     * @param string $table the table that the new column will be added to. The table name will be properly quoted by the method.
     * @param string $column the name of the new column. The name will be properly quoted by the method.
     * @param string $type the column type. The {@link getColumnType} method will be invoked to convert abstract column type (if any)
     * into the physical one. Anything that is not recognized as abstract type will be kept in the generated SQL.
     * For example, 'string' will be turned into 'varchar(255)', while 'string not null' will become 'varchar(255) not null'.
     */
    public function addColumn($table, $column, $type) {
        $tablesArr = $this->getTables();
        if (in_array($table, $tablesArr)) {
            parent::addColumn($table, $column, $type);
        } else {
            return true;
        }
    }

    /**
     * get admin user id
     *  
     */
    public function getSuperUserId() {
        $con = $this->getConnection();
        $sql = "Select user_id,user_name from user where user_email='super@yahoo.com'";
        $command = $con->createCommand($sql);
        $row = $command->queryRow();
        return $row;
    }

    /**
     * 
     * @param type $sql
     * @return type 
     */
    public function getQueryRow($sql) {
        $con = $this->getConnection();
        $command = $con->createCommand($sql);
        $row = $command->queryRow();
        return $row;
    }

    /**
     * 
     * @param type $sql
     * @return type 
     * get all data
     */
    public function getQueryAll($sql) {
        $con = $this->getConnection();
        $command = $con->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }

    /**
     * 
     * @param type $table
     * @param type $columns
     * @param type $key
     * @param type $val
     * @param type $$condition e.g where
     * @return type 
     * will be used to fetch all records 
     * against table with key pair value
     */
    public function findAllRecords($table, $columns, $key, $val, $condition = "") {
        $connection = $this->getConnection();
        $select_cols = implode(",", $columns);
        $sql = "Select $select_cols from " . $table . " " . $condition;
        $command = $connection->createCommand($sql);
        $rows = $command->queryAll();
        $data = array();
        foreach ($rows as $row) {
            $data[str_replace(" ", "_", $row[$key])] = $row[$val];
        }
        return $data;
    }

    /**
     *
     * @param type $table
     * @param type $columns 
     * extra common will be coverd here
     */
    public function insertRow($table, $columns) {
        $user_row = $this->getSuperUserId();
        if (empty($user_row['user_id'])) {
            $user_row['user_id'] = 1;
        }
        $common_column = array(
            "create_time" => date("Y-m-d H:i:s"),
            "create_user_id" => $user_row['user_id'],
            "update_time" => date("Y-m-d H:i:s"),
            "update_user_id" => $user_row['user_id'],
        );
        $columns = array_merge($columns, $common_column);
        $this->insert($table, $columns);
    }

    /**
     *  read json file
     */
    public function readJsonData($file) {
        $dataFile = Yii::app()->basePath . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR . $file;

        $fh = fopen($dataFile, 'r');
        $theData = fread($fh, filesize($dataFile));
        fclose($fh);
        return $theData;
    }

}

?>
