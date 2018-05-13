<?php

class Database
{
    public $conn;
    public $select='*';
    public $where='';
    public $param=[];
    public $limit='';
    public $order_by='';

    public function __construct()
    {
        try {
            $this->conn = new PDO('mysql:host=localhost;dbname=mbdda_egg', 'root', '');
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }
    }

    public function get($table = '')
    {
        try {
            $query = "SELECT $this->select FROM `$table` $this->where $this->limit";
            $stmt = $this->conn->prepare($query);
            $stmt->execute(array_values($this->param));
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getAll($table = '')
    {
        try {
            $query = "SELECT $this->select FROM `$table` $this->where $this->order_by $this->limit";
            $stmt = $this->conn->prepare($query);
            $stmt->execute(array_values($this->param));
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function insert($table = '', $request_data = [])
    {
        try {
            $request_values = implode(', ', $request_data);
            $request_colums = implode(', ', array_keys($request_data));
            $query = "INSERT INTO $table ($request_colums) VALUES ($request_values)";
            echo $query;
        } catch (PDOException $e) {
            echo $e->getMessage;
        }
    }

    public function update($table = '', $request_data = [])
    {
        try {
            $query = "UPDATE $table SET  $this->where ";
        } catch (PDOException $e) {
            echo $e->getMessage;
        }
    }

    public function select($param = [])
    {
        $this->select= implode(',', $param);
        return $this;
    }

    public function where($param = [])
    {
        $this->param = $param;
        $w='';
        foreach ($param as $col => $value) {
            $w.= $col.'= ? AND ';
        }
        $this->where= 'WHERE '.trim($w, 'AND ');
        return $this;
    }
    
    public function rowCount($table='') {
        try {
            $query = "SELECT * FROM `$table` $this->where ";
            $stmt = $this->conn->query($query);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function limit($offset = '', $total_no = '')
    {
        $this->limit = 'LIMIT '.$offset.','.$total_no;
        return $this;
    }

    public function order_by($column = '', $order = '')
    {
        $this->order_by = 'ORDER BY '.$column.' '.$order;
        return $this;
    }
}
