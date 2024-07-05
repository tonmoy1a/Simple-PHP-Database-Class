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
            $this->conn = new PDO('mysql:host=localhost;dbname=DB_NAME', 'DB_USER', 'DB_PASSWORD');
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }
    }

    public function get($table = '')
    {
        try {
            $query = "SELECT $this->select FROM `$table` $this->where $this->order_by LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->execute(array_values($this->param));
            $this->resetAllProperty();
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
            $this->resetAllProperty();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
    
    public function rowQuery($query, $data, $is_single_row=false)
    {
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute(array_values($data));
            $this->resetAllProperty();
            if($is_single_row){
                return $stmt->fetch(PDO::FETCH_ASSOC);
            }else{
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function insert($table = '', $request_data = [])
    {
        try {
            $request_values = implode(', ', $request_data);
            $comma_values = trim(str_repeat('?,', count($request_data)), ',');
            $request_colums = implode(', ', array_keys($request_data));
            $query = "INSERT INTO $table ($request_colums) VALUES ($comma_values)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute(array_values($request_data));
            $this->resetAllProperty();
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            echo $e->getMessage;
        }
    }

    public function update($table = '', $request_data = [])
    {
        $set_data;
        foreach($request_data as $key => $data){
            $set_data .= $key.'= ?, ';
        }
        $set_data = trim($set_data, ', ');
        
        $values = array_merge(array_values($request_data),array_values($this->param));

        $query = "UPDATE $table SET $set_data $this->where ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute($values);
        $this->resetAllProperty();
        
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
            $stmt = $this->conn->prepare($query);
            $stmt->execute(array_values($this->param));
            $this->resetAllProperty();
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
    
    private function resetAllProperty(){
        $this->select='*';
        $this->where='';
        $this->param=[];
        $this->limit='';
        $this->order_by='';
    }
}