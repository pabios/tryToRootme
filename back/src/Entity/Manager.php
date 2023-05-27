<?php
namespace Pabiosoft\Entity;

class Manager
{
    private $db;

    protected function dbConnect()
    {
        try{
            $this->db = new \PDO('mysql:host=localhost;dbname=secu;charset=utf8', 'pabios', 'pass');
            return $this->db;
        }catch(\Exception $e){
            echo ' impossible de se connecter';
        }
    }
}