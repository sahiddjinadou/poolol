<?php
namespace controllers;
trait utils
{
    public function Conect_verif()
    {
        if(!isset($_SESSION['auth'])){
            header("location: index.php");
            exit();
        } 
    }
    public function GetLinks()
    {
        $NavModel=New \models\nav_links();
        $sessionData=json_decode($_SESSION['auth']);// le json_decode return un objet
        if(!isset($sessionData->role)){
            return $NavModel->GetByRole(0);// la base de donné envoi les lien qui ont le role 0
        }else{
            return $NavModel->GetByRole($sessionData->role);// la basere tourne  les liens qui ont le roles appropriés
        }
        
    }
}