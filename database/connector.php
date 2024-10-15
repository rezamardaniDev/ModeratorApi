<?php

class Connection
{
    public $hostname = 'localhost';
    public $username = 'faraitir_root';
    public $password = 'mardani80';
    public $database = 'faraitir_moderator';
    public $db;

    public $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
    ];
    public function __construct()
    {
        $this->db = new PDO("mysql:host={$this->hostname};dbname={$this->database};charset=UTF8", $this->username, $this->password, $this->options);
    }
}
