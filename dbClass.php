<?php

class DBConnect {
        
        protected $_mysql;
        protected $_query;
        protected $_where = array();

public function __construct($host, $username, $password, $db) {
                $this->_mysql = new PDO("mysql:host=$host;dbname=$db", $username, $password);
                $this->_mysql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

function rquery($query, $params = NULL, $trace=false) {
                $this->_query = $query;
                $stmt = $this->_prepareQuery();
                if ($trace) {
            error_log("stmt:" . print_r($stmt) . print_r($params));
        }

$stmt->execute($params);
                
                $results = $this->_dynamicBindResults($stmt);

                return $results;
        }

        function dbString($array) {
                $stringArray = [];
                $string = '';
                foreach ($array as $key => $input) {
                        array_push($stringArray, $key.'=:'.$key);
                }
                return implode($stringArray, ',');
        }

function getValue($ColumnTitle, $id, $Table) {
                $queryString = 'SELECT '.$ColumnTitle.' FROM '.$Table.' WHERE id='.$id;
                $this->_query = filter_var($queryString, FILTER_SANITIZE_STRING);
                $stmt = $this->_prepareQuery();
                $stmt->execute();
                $results = $this->_dynamicBindResults($stmt);

                foreach($results as $row) {
                        return $row[$ColumnTitle];
                }
        }

function aquery($query, $params = NULL) {
                
                $this->_query = filter_var($query, FILTER_SANITIZE_STRING);
                $stmt = $this->_prepareQuery();
                $results = $stmt->execute($params);     
                
                return $results;
                
        }

function insertID() {
                return $this->_mysql->lastInsertId();   }

        
        protected function _dynamicBindResults($stmt) {
                while($row = $stmt->fetch(PDO::FETCH_OBJ)) {
                        $x = array();
                        foreach($row as $key=>$value) { 
                          $x[$key] = $value;
                        }  
                        $results[] = $x;
                }
                return $results;
        }
        
        protected function _prepareQuery() {
                if( !$stmt = $this->_mysql->prepare($this->_query) ) {
                        trigger_error('Problem Preparing Query', E_USER_ERROR);
                }
                return $stmt;
        }

function __destruct() {
                        
        }
}

?>

