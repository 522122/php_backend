<?php

class Model {

    private $db;
    private $table;

    private $model = null;
    private $conditions = null;
    private $limit = null;
    private $offset = null;

    function __construct($db, $table) {
        $this->db = $db;
        $this->table = $table;
    }

    public function conditions($conditions) {
        $this->conditions = $conditions;
        return $this;
    }

    public function order($order) {
        $this->order = $order;
        return $this;
    }

    public function limit($limit) {
        $this->limit = $limit;
        return $this;
    }

    public function offset($offset) {
        $this->offset = $offset;
        return $this;
    }

    public function model($model) {
        $this->model = $model;
        return $this;
    }

    public function select() {
        return $this->exec($this->get_select_sql());
    }

    public function insert() {
        return $this->exec($this->get_insert_sql());
    }

    public function update() {
        return $this->exec($this->get_update_sql());
    }

    public function delete() {
        return $this->exec($this->get_delete_sql());
    }

    private function exec($sql) {
        $result = pg_query_params($this->db, $sql, $this->get_params());

        $results = array();
        
        if (!$result) {
            return array();
        }

        while ($row = pg_fetch_assoc($result)) {
            array_push($results, $row);
        }
        
        return $results;
    }

    private function get_params() {
        $params = array();

        if ( $this->model ) {
            $params = array_merge($params, array_values($this->model));
        }
        if ( $this->conditions ) {
            $params = array_merge($params, array_values($this->conditions));
        }

        return $params;
    }

    private function get_conditions($i=1) {
        $internali = 1;
        $conditions = "";

        foreach($this->conditions as $key=>$value) {
            $conditions .= "\"$key\"=$$i ";
            if ($internali < count($this->conditions)) {
                $conditions .= "AND ";
            }
            $internali++;
            $i++;
        }

        return $conditions;
    }

    private function get_order() {
        $order = "";
        foreach($this->order as $key=>$value) {
            $order .= "$key $value ";
        }
        return $order;
    }

    private function get_select_sql() {
        $sql = "SELECT * FROM $this->table";

        if ( $this->conditions ) {
            $sql .= " WHERE {$this->get_conditions()}";
        }
        if ( $this->order ) {
            $sql .= " ORDER BY {$this->get_order()}";
        }
        if ( $this->limit ) {
            $sql .= " LIMIT {$this->limit}";
        }
        if ( $this->offset ) {
            $sql .= " OFFSET {$this->offset}";
        }

        return $sql;
    }

    private function get_insert_sql() {
        $keys = "";
        $values = "";
        $i = 1;

        foreach($this->model as $key=>$value) {
            $keys .= "\"$key\"";
            $values .= "$$i";
            if ($i < count($this->model)) {
                $keys .= ", ";
                $values .= ", ";
            }
            $i++;
        }

        return "INSERT INTO \"$this->table\" ($keys) VALUES ($values) returning *";
    }

    private function get_delete_sql() {
        $sql = "DELETE FROM \"$this->table\"";

        if ( $this->conditions ) {
            $sql .= " WHERE {$this->get_conditions()}";
        }

        return $sql . " returning *";

    }

    private function get_update_sql() {
        $sets = "";
        $i = 1;

        $sql = "UPDATE \"{$this->table}\" SET ";

        foreach($this->model as $key=>$value) {
            $sets .= "\"$key\"=$$i";
            if ($i < count($this->model)) {
                $sets .= ", ";
            }
            $i++;
        }

        $sql .= "{$sets}";

        if ( $this->conditions ) {
            $sql .= " WHERE {$this->get_conditions($i)}";
        }

        return $sql . " returning *";
    }

}
