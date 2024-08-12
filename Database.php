<?php

class Database 
{
    private $host     = 'localhost';
    private $username = 'root';
    private $password = ''; 
    private $database = 'demo_intern';
    private $connection;

    public function __construct() {
        $this->connect();
    }

    private function connect() 
    {
        $this->connection = new mysqli($this->host, $this->username, $this->password, $this->database);

        if ($this->connection->connect_error) {
            $this->connectionStatus['error'] = "Connection failed: " . $this->connection->connect_error;
        } 
        
        {
            $this->connectionStatus['success'] = "Connected successfully";
        }
    }
    
    public function getConnection() 
    {
        return $this->connection;
    }

    public function getConnectionStatus() 
    {
        return [
            'error' => $GLOBALS['db_error'] ?? '',
            'success' => $GLOBALS['db_success'] ?? ''
        ];
    }
}
