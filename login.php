<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>log in</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
    <style>
        #alert{
            text-align: center !important;
            margin-top: 5px;
        }
    </style>
</head>
<body>
<?php  require_once 'nav.php';?>
<main class="container mt-3">
<form method="post"> 
    <label  class="form-label" for="">Email:</label>
    <input required type="text"  class="form-control" name="email2" id="">
    <label  class="form-label" for="">Password:</label>
    <input required  class="form-control" type="password" name="passing">
    <button class="btn btn-outline-success mt-3" name="go" type="submit">log in</button>
    <a class="btn btn-outline-dark mt-3" href="https://127.0.0.1/qmaker/regestration.php">sign up instead</a>
    <a class="btn btn-outline-warning mt-3" href="https://127.0.0.1/qmaker/repass.php">forgot your password?</a>
</form>
<?php
if(isset($_POST['go'])){
    $username='root';
    $password='';

    $database= new PDO("mysql:host=localhost; dbname=exams;",$username,$password);


    $passmd = hash("sha256",$_POST['passing']);
    $log=$database->prepare("SELECT * FROM users WHERE email =:email AND password =:pass LIMIT 1");

    $log->bindParam('email',$_POST['email2']);
    $log->bindParam('pass', $passmd);
    $log->execute();
    if($log->rowCount()>0){
        $info=$log->fetchObject();
        if($info->activated =='1'){
            echo'<br>';
            echo'WELCOME:'.$info->name;
            session_start();
            $_SESSION['info']=$info;
            if($info->role == 'Teacher'){
                header("location:https://".$ip."/qmaker/teacher/tpage.php",true);
            }elseif($info->role == 'Student'){
                header("location:https://".$ip."/qmaker/student/stpage.php",true);

            }else{
                echo "error!";
            }

        }else{
            echo'<div id="alert" class="alert alert-warning" role="alert">
            You have to chck your email first!!
            </div>
            ';
        }
    }else{
        echo'<div id="alert" class="alert alert-danger" role="alert">
        wrong email or passowrd!
        </div>
        ';
    }
}
?>
</main>

</form>
</body>
</html>
