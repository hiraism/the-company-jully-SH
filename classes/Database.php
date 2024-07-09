<?php

  class Database{
    private $server_name = "localhost"; //Mamp that is in our computer
    private $username = "root";
    private $password = "root"; //add "root" for Mac
    private $db_name = "the_company";
    public $conn;

    public function __construct(){
      $this->conn = new mysqli($this->server_name, $this->username, $this->password, $this->db_name);

      if($this->conn->connect_error){
        die("Unable to connect to database: " . $this->conn->connect_error);
      }
    }
  }