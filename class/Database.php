<?php

class Database 
{	
	public $conn;
	public $where='';
	public $param=[];
	public $limit='';
	public $order_by='';

	public function __construct() {
        try {
	    	$this->conn = new PDO('mysql:host=localhost;dbname=bitm_course', 'root', '');
	    	$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
	    	echo 'ERROR: ' . $e->getMessage();
		}
    }

	public function get($table=''){
		try {
            $query = "SELECT * FROM `$table` $this->where $this->limit";
            echo $query;
            $stmt = $this->conn->prepare($query);
            $stmt->execute(array_values($this->param));
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
	}

	public function getAll($table=''){
		try {
            $query = "SELECT * FROM `$table` $this->where $this->order_by $this->limit";
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
		return $this;
	}

	public function limit($offset='',$total_no=''){
		$this->limit = 'LIMIT '.$offset.','.$total_no;
		return $this;
	}

	public function order_by($column='', $order=''){
		$this->order_by = 'ORDER BY '.$column.' '.$order;
		return $this;
	}
}