<?php

class Database 
{	
	public $conn;
	public $where='';
	public $param=[];

	public function __construct() {
        if (empty(session_id())) {
            session_start();
        }
        try {
	    	$this->conn = new PDO('mysql:host=localhost;dbname=bitm_course', 'root', '');
	    	$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
	    	echo 'ERROR: ' . $e->getMessage();
		}
    }

	public function get($table=''){
		try {
            $query = "SELECT * FROM `users` $this->where ";
            echo $query;
            $stmt = $this->conn->prepare($query);
            $stmt->execute(array_values($this->param));
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
	}

	public function where($param=[]){
		$this->param = $param;
		$w='';
		foreach ($param as $col => $value) {
			$w.= $col.'= ? AND ';
		}
		$this->where= 'WHERE '.trim($w,'AND ');

	}
}