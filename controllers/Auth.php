<?php
namespace controllers;
class auth
{
    private $UserModel;
    private $AdminsModel;
    function __construct()// j'execute automatiquement  le construct qui me permet d'aller vers le login
    {
        $this->UserModel=new \models\users();
        $this->AdminsModel=new \models\admin();

        if(isset($_GET['target'])){
            $target=$_GET['target'];
            $this->$target(); 
        }else{
            $this->login();
        }
    }
    
    public function login()
    {
       
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST["id_user"]) && isset($_POST["pwd"])) {
                $user = $this->UserModel->GetUserByLogin($_POST["id_user"]);//je recupere l'identifiant de l'utilisateur
                $admin=$this->AdminsModel->GetByAdLogin($_POST["id_user"]);//je recupere l'identifiant de l'admin
                if ($user) {// si c'est l'utilisateur
                    if (password_verify($_POST["pwd"], $user['Mdp'])){
                        $_SESSION['auth']=json_encode(['user'=>$user['Identifiant'],'id'=>$user['Id']]);// je renferme dans le $session les infor de l'utilisateur connecté
                        header("location: index.php?goto=reservation");//je l'oriente vers reservation
                        exit();
                    }
                }elseif($admin){  // si c'est l'admin .c'est ici que le meme formulaire dissocie l'utilisateur
                    if($admin['Passwd']!=NULL){
                        if (password_verify($_POST["pwd"], $admin['Passwd'])){
                            $_SESSION['auth']=json_encode(['user'=>$admin['AdLogin'],'id'=>$admin['Id'],'role'=>$admin['AdRole']]);
                            header("location: index.php?goto=admin");// je l'oriente vers la page admin
                            exit();
                        }
                    }else{
                        header("location: index.php?goto=auth&target=UpdatePassword&id=".$admin['Id']);//si le retour  de la base pas rapport à l'entre  de l'utilisateur ,je le passe dans la fonction updatepassword bien evidemmment avec la valeur de son identifient
                        exit();
                    }
                }
            }
        }
        $template ='views/page/connexion.phtml';
        include_once 'views/main.phtml';
    }

    public function UpdatePassword()
    {
        if(isset($_GET['id'])){

            $admin=$this->AdminsModel->GetById($_GET['id']); 
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (stripslashes(trim($_POST["pwd"])) == stripslashes(trim($_POST["cpwd"]))) {
                $password = password_hash(stripslashes(trim($_POST["pwd"])), PASSWORD_DEFAULT);
                $this->AdminsModel->UpdateAdPasswd([$password,$_POST["user"]]);
                $this->login();
                exit();
            }
        }
        $template ='views/page/PasswordForm.phtml';
        include_once 'views/main.phtml';
    }
}