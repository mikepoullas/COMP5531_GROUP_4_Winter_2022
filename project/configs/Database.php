<?php
include_once('db.php');

class DB
{
    private $host   = DB_HOST;
    private $user   = DB_USER;
    private $pass   = DB_PASS;
    private $dbname = DB_NAME;

    public $conn;
    public $error;

    public function __construct()
    {
        $this->connect();
    }

    private function connect()
    {
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->dbname);
        if (!$this->conn) {
            echo "Connection fail: " . $this->conn->connect_error;
            return false;
        }
    }

    // Select or Read data
    public function select($query)
    {
        $select = $this->conn->query($query) or die($this->conn->error . __LINE__);
        if ($select->num_rows > 0) {
            return $select;
        } else {
            return false;
        }
    }

    // Insert Data
    public function insert($query)
    {
        $insert = $this->conn->query($query) or die($this->conn->error . __LINE__);
        if ($insert) {
            return $insert;
        } else {
            return false;
        }
    }

    // Update data
    public function update($query)
    {
        $update = $this->conn->query($query) or die($this->conn->error . __LINE__);
        if ($update) {
            return $update;
        } else {
            return false;
        }
    }

    // Delete data
    public function delete($query)
    {
        $delete = $this->conn->query($query) or die($this->conn->error . __LINE__);
        if ($delete->num_rows > 0) {
            return $delete;
        } else {
            return false;
        }
    }
}
