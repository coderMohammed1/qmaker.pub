<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">

    <style>
        #sh{
            text-align: center !important;
            border: solid red 1px;
        }

        #alert{
            text-align: center !important;
        }
        #btn{
            width: 50% !important;
        }

        #logo{
            width: 50px;
        }

        #bar{
            width: 100% !important;
            justify-content: center;
        }

        #f1{
            display: flex;
            justify-content: center;
        }
    </style>
</head>
<body>
<?php require_once '../nav.php'; ?>


<main class="m-auto container" >
<?php
    

    if(isset($_SESSION["qinfo"])){
        $_SESSION["qinfo"] = "none";
    }
    if(isset($_SESSION['info']) && $_SESSION['info']->role==='Teacher'){
        $info2=$_SESSION['info'];

        echo'<div id="sh" class="shadow p-3 mb-1 bg-body rounded"> Welcome'.' '.$info2->name.'</div>';

        echo'<form id = "f1" method="POST">
        <button class="btn btn-outline-secondary mt-3 " id="btn" name="out" type="submit">log out</button>
        <a id="btn" class="btn btn-outline-warning mt-3" name="update" href="https://'.$ip.'/qmaker/profile.php">update your information</a>
        </form>';
        echo "</br>";

       echo '<div>
            <form action="/qmaker/teacher/maker.php" method = "post">
        
                    <label class="form-label" for="">exam Name:</label>
                    <input maxlength="30" class="form-control" type="text" name="name" required>
                    
        
                    <label class="form-label" for="">passcode:</label>
                    <input class="form-control" minlength="6" type="password" placeholder = "leave it empty id you do not want a password" title = "do not use a password that you used before" name="passcode">

                    <label  class="form-label" for="">Number of allowed attempts:</label>
                   <input class="form-control" type="number" value = "1" name="attmpts">
                   <br>
                   <button class="btn btn-outline-dark" type="submit"  name="make" id="mo">make</button>
                    
            </form>
        </div>';
        
        if(isset($_POST['out'])){
            session_destroy();
            session_unset();
            echo '<script>window.location.href = "https://'.$ip.'/qmaker/login.php";</script>';
        }
        
    }else{
        header("location:https://".$ip."/qmaker/login.php",true);
        die("");
    }
?> 
</main>
</body>
</html>