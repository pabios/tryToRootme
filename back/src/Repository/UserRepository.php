<?php
namespace Pabiosoft\Repository;

use Pabiosoft\Entity\Manager;
use Pabiosoft\Entity\User;

class  UserRepository  extends Manager{
    public function getUsers()
    {
        $db = $this->dbConnect();
        $req = $db->query('SELECT id, pseudo,age, description,photo,role  FROM user ORDER BY id DESC ');
        return $req;
    }

    public  function addUser(User $user){
        $sql = '
            INSERT INTO `user` (`pseudo`, `age`,`description`,`photo`,`role`)
            VALUES (:pseudo, :age,:description,:photo,:role)
        ';
        $q = $this->dbConnect()->prepare($sql);
        $q->bindValue(':pseudo', $user->getPseudo(), \PDO::PARAM_STR);
        $q->bindValue(':age', $user->getAge(), \PDO::PARAM_STR);
        $q->bindValue(':description', $user->getDescription(), \PDO::PARAM_STR);
        $q->bindValue(':photo', $user->getPseudo(), \PDO::PARAM_STR);
        $q->bindValue(':role', $user->getRole(), \PDO::PARAM_STR);
        $q->execute();
    }

    public  function  find(int $id){
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT id, pseudo, description,photo,role FROM user WHERE id = :id');
        $req->bindValue('id',$id,\PDO::PARAM_INT);
        $req->execute();

        $user = $req->fetch();

        if ($user) {
            return $user;
        } else {
            return 0;
        }
    }

    public function deleteOne(int $id){
        if($this->find($id) === 0){
           return 'aucun user pour cet id';
        }

        $db = $this->dbConnect();
        $req = $db->prepare('DELETE FROM user WHERE id = :id');
        $req->bindValue('id',$id,\PDO::PARAM_INT);
        $req->execute();

        return 'success';
    }

    public function checkExistedUser(string $pseudoSecured, string $passwordSecuredReversed)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT id,role,description pseudo,password FROM user WHERE pseudo=:pseudo AND password=:password LIMIT 1');
        $req->bindValue('pseudo',$pseudoSecured,\PDO::PARAM_STR);
        $req->bindValue('password',$passwordSecuredReversed,\PDO::PARAM_STR);
        $req->execute();

        $response = $req->fetch();



//        return  ($req->rowCount() ===1);
        return $response;
    }




}