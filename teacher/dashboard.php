<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Exams</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        #sh{
            margin-bottom: 25px;
            width:90%;
            margin-left:6.7%;
            text-align: center !important;
            border: solid red 1px;
        }
    </style>
</head>
<body>
    <?php require_once '../nav.php'; ?>

    <?php
        $username = 'root';
        $password = '';
        $database = new PDO("mysql:host=localhost; dbname=exams;", $username, $password);
        
        if(isset($_SESSION["info"])){
            echo'<div id="sh" class="shadow p-3 mb-1 bg-body rounded">Exams Dashboard!</div>';
            $data = $database->prepare("SELECT name,NumOfAttempts,ID,passcode FROM quizzes where tid=:id");
            $data->bindParam("id",$_SESSION["info"]->ID);

            if($data->execute()){
                echo "<main id = 'main' style='display: flex; flex-wrap: wrap; margin-left: 7%;'>";
                    foreach ($data as $datium){
                        echo '<div style="width: 300px; margin-left: 15px; margin-top: 4px; border: 2px blue solid; padding-bottom: 4.5px; padding-left: 10px; margin-bottom:7px">
                            <div style="border-bottom: 1px red solid;">
                                <p>name: '.$datium["name"].'</p>
                            </div>

                            <div style="border-bottom: 1px red solid;">
                                <p>number of attempts: '.$datium["NumOfAttempts"].'</p>
                            </div>

                            <div style="border-bottom: 1px red solid; margin-bottom: 3.5px;">
                                <p>Password: '.$datium["passcode"].'</p>
                            </div>

                            <form action = "/qmaker/teacher/stats.php" method = "post">
                                <input type="hidden" name="Name" value="'.$datium["name"].'">
                                <input type="hidden" name="stats" value="'.$datium["ID"].'">

                                <div>
                                    <button name = "btn" type = "submit" class="btn btn-info">show more information!</button> 
                                </div>
                            </form>    
                            </div>';

                            //carefule about the buitton value!
                    }
                echo "</main>";

            }else{
                echo "error!";
            }
        }else{
            echo '<script>window.location.href = "https://'.$ip.'/qmaker/login.php";</script>';
        }

        

    ?>
</body>
</html>