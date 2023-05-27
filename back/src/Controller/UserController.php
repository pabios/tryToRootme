<?php
namespace Pabiosoft\Controller;

use Firebase\JWT\JWT;
use http\Cookie;
use Pabiosoft\Controller\SecurityController;
use \Pabiosoft\Entity\User;
use \Pabiosoft\Repository\UserRepository;
use Pabiosoft\App\Config\Key;

class UserController
{
    /**
     * @return void liste des users format json
     */
    public function users()
    {

        $userRepository = new UserRepository();
        $user = $userRepository->getUsers();

        $security = new SecurityController();

        $token = $security->checkExistedToken();

        if(empty($token)){
            echo json_encode("aucun token envoyer",JSON_PRETTY_PRINT);
            return;
        }

        try {
            $key = Key::getSecretKey();
            $decodedToken = JWT::decode($token, $key, ['HS256']);

            $pseudo = $decodedToken->pseudo;
            $exp = $decodedToken->exp;
            $role = $decodedToken->role;
            $id = $decodedToken->id;


            $userData = $this->getUserById($id,true);
            if($userData !== $role){
                echo json_encode("Token incompatible", JSON_PRETTY_PRINT);
                return;
            }



            $response = [];
            foreach($user as $all ){
                $response[] = $all;
            }

            $responses = [];
            $i = 0;
            while ($i != count($response)){
                $responses[] = array(
                    "id"=> $response[$i]["id"],
                    "pseudo"=> $response[$i]["pseudo"],
                    "age"=> $response[$i]["age"],
                    "description"=> $response[$i]["description"],
                    "photo"=> $response[$i]["photo"],
                    "role"=> $response[$i]["role"],
                );
                $i++;
            }

            //$final = '[{"id": "1","pseudo":"toto",..}]';
            $security->render($responses);

        } catch (\Exception $e) {
            echo json_encode("Token invalide", JSON_PRETTY_PRINT);
            return;
        }
    }


    /**
     * @return void ajout d'un user
     */
    public function newUser():void{

        $msg = '';
        $user = new User();
        $security = new SecurityController();

        if (!empty($_POST)){

            extract($_POST);

            if(!empty($pseudo) AND !empty($password) AND !empty($age) AND !empty($description) AND !empty($photo) AND !empty($role)){

                $pseudo = $security->secureChaine($pseudo);
                $description = $security->secureChaine($description);
                $role = $security->secureChaine($role);
                $password = $security->securePassword($password);

                $age = $security->secureAge($age);
                if($age === -1){
                    $msg = 'votre age doit etre positif';
                }
                if(empty($_FILES)){
                    exit('aucun fichier envoyer');
                }
                $photo = $security->uploadFile($_FILES['photo']);
                if(!is_array($photo)){
                    $msg = $photo;
                }



                if(!empty($msg)){
                    exit($msg);
                }

                $user->setPseudo($pseudo);
                $user->setAge($age);
                $user->setPassword($password);

                $user->setDescription($description);
                $user->setPhoto($photo);
                $user->setRole($role);


                $userRepo = new UserRepository();
                $userRepo->addUser($user);

                $msg = 'un nouveau utilisateur vient d\'etre ajouter ';
            }else{
                $msg = 'oups toutes les informations doivent exister et contenir des valeurs';
            }
        }else{
           $msg= 'aucune donnee y est associer';
        }

        $security->render($msg);
    }


    public  function login(){
        $security = new SecurityController();
        $security->addHeader();
        $response = '';

        if (!empty($_POST)){

            extract($_POST);

            if(!empty($pseudo) AND !empty($password)){
                $pseudo = $security->secureChaine($pseudo);
                $password = $security->secureChaine($password);

                $h = $security->securePassword($password);


                   $userRepo = new UserRepository();
                   $rep = $userRepo->checkExistedUser($pseudo,$h);

                   if(!empty($rep['id'])){
                      $roleUser = json_decode($this->getUserById(intval($rep['id']),true)) ;

                       $payload = array(
                                    "pseudo" => $pseudo,
                                    "exp" => time() + 3600, //   dans 1 heure
                                    "role" => $roleUser,
                                    "id"  => $rep['id']
                                );
                        $security->generateToken($payload);


                        $security->render($roleUser);
                      // $response = 'login succes';

                   }else{
                       $response = 'pseudo ou mot de passe erreur';
                   }


            }else{
                $response = 'oups toutes les informations doivent exister et contenir des valeurs';
            }

        }else{
            $response = 'aucune donnee y est associer';
        }

        $security->render($response);
    }
    public  function deleteUser($id):void{


        $userRepo = new UserRepository();
        $security = new SecurityController();
        $id = $security->secureInt($id);

        $delete = $userRepo->deleteOne($id);
        $response = ( $delete === 'success') ? 'Utilisateur bien supprimer' : $delete;

        $security->render($response);
    }

    public  function getUserById($id,$role=false){


        $userRepo = new UserRepository();
        $security = new SecurityController();
        $id = $security->secureInt($id);
        $security->addHeader();
        $rep = $userRepo->find($id);

        if($rep === 0){
            exit("aucun user pour cet id");
        }

        $final = array(
            "id" => $rep['id'],
            'pseudo'=>$rep['pseudo'],
            'description'=>$rep['description'],
            'role'=> $rep['role'],
            'photo'=>$rep['photo'],
        );

        $response = (object) $final;

        if($role){
            return json_encode($final['role'],JSON_PRETTY_PRINT);
        }
        $security->render($response);


    }

}