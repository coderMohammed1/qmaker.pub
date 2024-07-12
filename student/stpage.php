<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student page</title>
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
    
    $username = 'root';
    $password = '';
    $database = new PDO("mysql:host=localhost; dbname=exams;", $username, $password);

    if(isset($_SESSION["info"])){
        $info2 = $_SESSION["info"];
        
        echo '<br>';
        echo'<div id="sh" class="shadow p-3 mb-1 bg-body rounded"> Welcome'.' '.$info2->name.'</div>';

        echo'<form id = "f1" method="POST">
            <button class="btn btn-outline-secondary mt-3 " id="btn" name="out" type="submit">log out</button>
            <a id="btn" class="btn btn-outline-warning mt-3" name="update" href="https://'.$ip.'/qmaker/profile.php">update your information</a>
        </form>';

        echo "</br>";

        echo'<form  method="post">
            <label class="form-label mt-4 ">SEARCH:</label>
                <input class="form-control " type="text" name="search" placeholder="exam or teacher name:"/>
                <button class="btn btn-dark mt-1 w-100" type="submit" name="send01">search</button>
            </form>';

            if(isset($_POST["send01"])){

                $results=$database->prepare("SELECT * FROM quizzes JOIN users ON quizzes.tid = users.ID  WHERE quizzes.name LIKE :qname OR users.name = :tname ORDER BY quizzes.ID DESC limit 25");
                $val="%".$_POST['search']."%";

                $results->bindParam('qname',$val);
                $results->bindParam('tname',$val);
                $results->execute();

                if($results->rowCount()>0){

                    foreach($results as $result){  
                        // echo '<pre>';
                        //     print_r($result);
                        // echo '</pre>';

                        echo '
                            <div class="container mt-4" style="max-width: 350px;">
                            <div class="text-center">
                                <img id="img" src="study_well.jpg" alt="error" class="img-fluid rounded" style="max-width: 100%; height: auto;">
                            </div>
                    
                            <div class="mt-3">
                                <div class="d-flex align-items-center mb-2">
                                    <p class="mb-0">name:</p>
                                    <p class="mb-0 ms-2">'.sanitize($result['1']).'</p>
                                </div>
                    
                                <div class="d-flex align-items-center mb-2">
                                    <p class="mb-0">Teacher name:</p>
                                    <p class="mb-0 ms-2">'.sanitize($result["name"]).'</p>
                                </div>
                    
                                <div>
                                    <form method = "POST">
                                         <button type = "submit" name = "go" class="btn btn-secondary w-100" value = "'.$result["0"].'">go</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                     ';

                    }
                    
                    echo "<br>";

                }else{
                    echo'<div id="alert" class="alert alert-danger mt-2" role="alert">
                            No data found
                        </div>
                    ';
                }

            }

            if(isset($_POST['out'])){

                session_destroy();
                session_unset();
                echo '<script>window.location.href = "https://'.$ip.'/qmaker/login.php";</script>';

            }

            if(isset($_POST["go"])){

                $_SESSION["qid"] = $_POST["go"];
                header("location:https://".$ip."/qmaker/student/authen.php",true);

            }
    }else{
        header("location:https://".$ip."/qmaker/login.php",true);
    }

?>

</main>

</body>
</html>