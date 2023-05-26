<?php
namespace Pabiosoft\Controller;
class SecurityController{



    public  function render($response){
        header("Access-Control-Allow-Headers: Authorization, Content-Type");
        header("Access-Control-Allow-Origin : *");
        header('Content-Type: application/json' );
        if(empty($response)){
            return [];
        }
        echo json_encode($response,JSON_PRETTY_PRINT);
    }

    public function secureChaine($chaine){
        return  htmlspecialchars(stripcslashes($chaine));
    }

    public  function  secureInt($number){
        if(!intval($number)){
            die("La saisie doit imperativement etre de type entier");
        }
        return $number;
    }

    public  function  secureAge($age){
        if($this->secureInt($age)){
             if($age > 0){
                 return $age;
             }else{
                 return -1;
             }
        }
    }


    public  function  securePassword($password){
//      return password_hash($password, PASSWORD_BCRYPT); //@todo this later
       return hash('SHA512',$password);
    }
    public  function  reversePassword($password,$hash){
        return password_verify($password, $hash);
    }



    public function uploadFile($file)
    {

        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        $fileSize = $file['size'];
        $fileError = $file['error'];
        //$fileType = $file['type'];
        $fileExt = explode('.', $fileName);
        $fileActualExt = strtolower(end($fileExt));

        $allowed = array('png','jpg','jpeg');

        if (in_array($fileActualExt, $allowed)) {
            if ($fileError === 0) {
                if ($fileSize < 5000000) { // 5 mega
                    $fileNameNew = md5(uniqid('', true)) . "." . $fileActualExt;
                    $fileDestination = 'public/uploads/' . $fileNameNew;
                    move_uploaded_file($fileTmpName, $fileDestination);

                   return  [$fileDestination];
                } else {
                    return "Votre image est trop lourd";
                }
            } else {
                return "Une erreur c'est produit lors du televerssement";
            }
        } else {
            return "Ce type de fichier n'est pas permis ";
        }

    }
    public  function addHeader()
    {
        header("Access-Control-Allow-Headers: Authorization, Content-Type");
        header("Access-Control-Allow-Origin : *");
        header('Content-Type: application/json' );
    }



}