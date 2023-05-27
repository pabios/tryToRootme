<?php
namespace Pabiosoft\Entity;
use Pabiosoft\App\Config\Env;

class Manager
{
    private $db;
    private $host;
    private  $dbName;
    private $user;
    private $password;


    public  function  __construct()
    {
        $key = new Env();
        $this->host = $key::getDbHost();
        $this->password = $key::getDbPassword();
        $this->user = $key::getDbUsername();
        $this->dbName = $key::getDbName();

    }

    protected function dbConnect()
    {
        try{
            $this->db = new \PDO("mysql:host=$this->host;dbname=$this->dbName;charset=utf8", $this->user, $this->password);
            return $this->db;
        }catch(\Exception $e){
            echo ' impossible de se connecter';
        }
    }
}