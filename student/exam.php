<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>exams</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
    <style>

        input[type="radio"] {
            cursor: pointer;
        }

         body{
            background-color: rgb(240, 235, 248);
        }

        #alert{
            text-align: center !important;
        }

        #cont{
            background-color: white;
            margin-top: 6px !important;
            /* width: 40% !important; */
        }

        #qs{
            border-bottom: red 1px solid;
        }

        #sh{
            text-align: center !important;
            border: solid red 1px;
            width: 50% !important;
            margin: auto;
        }

        #question{
            word-wrap: break-word;
            white-space: pre-wrap;
            margin: 0 !important;
        }
    </style>

</head>
<body>
    
<?php require_once '../nav.php'; ?>

<?php

    $username = 'root';
    $password = '';
    $database = new PDO("mysql:host=localhost; dbname=exams;", $username, $password);

    if(isset($_SESSION["quizinfo"])){

        $quests = $database->prepare("SELECT * FROM questions LEFT JOIN qimgs ON questions.ID = qimgs.qid WHERE questions.qid = :id "); 
        
        $quests->bindParam("id",$_SESSION["quizinfo"]->ID);

        $options = $database->prepare("SELECT * FROM options WHERE quiz = :id");
        $options->bindParam("id",$_SESSION["quizinfo"]->ID);

        if($options->execute() && $quests->execute()){

            $x = 0;
            $n = 0;
            $j = 1;

            $e = 0;
            $essa = -1;
            echo '<br>';
            echo'<div id="sh" class="shadow p-3 mb-1 bg-body rounded"><h4> '.sanitize($_SESSION["quizinfo"]->name).'</h4></div>';

            echo '<div class="container" id = "cont">';
                echo '<form method = "post">';

                foreach($quests as $qs){

                    if($qs["img"] == null){
                        $imageUrl = '';
                    }else{
                        $imageUrl = "data:".$qs['itype'].";base64,".base64_encode($qs['img']);
                        
                    }

                    echo'<div id = "qs">';
                        
                        echo '<div style = "width:100%; hight:40%"; class="image-container"'. (!empty($imageUrl) ? '' : ' style="display: none;"') .'>
                             <img style = "width:100%;" src="'.htmlspecialchars($imageUrl, ENT_QUOTES, 'UTF-8').'" >
                         </div>';

                        echo '<div style = "color:red;">
                                <p id = "question">'.$j.':'.$qs["text"].'</p>
                                </div>
                            ';
                        if($qs["type"] == "mcq"){
                            $l = 1;
                            for ($i = $x; $i < $x + 4; $i++) {
                                if ($row = $options->fetch(PDO::FETCH_ASSOC)) {

                                    if($row["text"] != ""){
                                        
                                        echo '
                                            <div style = "display:flex;">
                                                <input required  style = "margin-right:7px; width:16px;" type="radio" name = "'.$n.'" value = "'.$row["ID"].'">
                                                <p>'.$row["text"].'</p>
                                            </div>
                                        ';
                                    }else{
                                        continue;
                                    }
                                    $l++;
                                }
                                
                            }
                            $n++; // this will be used to maintain the names system
                        }else{
                            echo'<div>
                                    <label class="form-label" for="">Answer:</label>
                                    <input class="form-control qs"  type="text" name="'.$essa.'">
                               </div>
                            ';
                            $e ++;
                            $essa--;
                        }
                        
                    echo'</div>';
                    echo "<br>";
                    $j ++;
                
            }

                echo '<input type = "hidden" name = "num" value = "'.$n.'">';
                echo '<input type = "hidden" name = "nume" value = "'.$e.'">';
                echo '<div><button type = "submit" class = "btn btn-info mb-2" name = "send">submit</button></div>';
                echo '</form>';
            echo "</div>";
        }else{
            echo '<div id="alert" class="alert alert-danger" role="alert">' .
                htmlspecialchars("An error has occurred!", ENT_QUOTES, 'UTF-8') . 
            '</div>';
        }

        if(isset($_POST["send"])){
           if(isset($_SESSION["numofat"]) && $_SESSION["numofat"] < $_SESSION["quizinfo"]->NumOfAttempts){
                // make sure of the number of attempts and increas it
            
                // get all correct IDs  for the quiz (mcq)
                $coreect = $database->prepare("SELECT ID FROM options WHERE quiz = :id AND iscorrect = 1 ORDER BY ID DESC  LIMIT :num "); // I will fix these limits and orders!
                $coreect->bindParam("id",$_SESSION["quizinfo"]->ID);

                // $coreect->bindValue("mc","mcq");
                $coreect->bindValue(":num", ($_POST["num"]+1), PDO::PARAM_INT);

            
                $c = $_POST["num"]-1;
                $score = 0;
                if($coreect->execute()){
                    foreach($coreect as $co){

                        if($co["ID"] == $_POST[$c]){
                            
                            $score ++;
                        }
                        // error_log("ID value: " . $co["ID"]);
                        // error_log("C value: " . $_POST[$c]);
                        
                        $c--;
                    }

                    $ecorrect = $database->prepare("SELECT ans FROM correct where quiz = :id ORDER BY ID DESC  LIMIT :enum"); // add order by pls
                    $ecorrect->bindParam("id",$_SESSION["quizinfo"]->ID);
                    
                    $ecorrect->bindValue(":enum", $_POST["nume"]+1, PDO::PARAM_INT);

                
                    $essa2 = $essa+1;
                    $c2 = 0;
                    if($ecorrect->execute()){
                        
                        foreach($ecorrect as $cor){
                            if(trim($cor["ans"]) == trim($_POST[$essa2])){
                                $c2++;
                                $essa2++;
                            }
                            
                        }
                        
                    }


                    // delete number of attempts and instead check if he has a record in this table 
                    $grade = $database->prepare("INSERT INTO attempt(student,score,qid,nofqs) VALUES(:stid,:sc,:quid,:nqs)");

                    $grade->bindParam("stid",$_SESSION["info"]->ID);
                    $grade->bindValue("sc",($score+$c2));

                    $grade->bindParam("quid",$_SESSION["quizinfo"]->ID);
                    $grade->bindValue("nqs",$_POST["num"]+$_POST["nume"]);

                

                    if($grade->execute()){
                        echo '<script>window.location.href = "https://'.$ip.'/qmaker/student/scores.php";</script>"';           

                    }else{

                        echo '<div id="alert" class="alert alert-danger" role="alert">' .
                            htmlspecialchars("An error has occurred#1!", ENT_QUOTES, 'UTF-8') . 
                        '</div>';
                    }


                    }else{
                        $errorInfo = $coreect->errorInfo();
                        echo '<div id="alert" class="alert alert-danger" role="alert">' .
                            htmlspecialchars("An error has occurred#2: " . implode(" - ", $errorInfo), ENT_QUOTES, 'UTF-8') .
                            '</div>';
                    }

                    $_SESSION["numofat"] ++;
            }else{
                echo '<div id="alert" class="alert alert-danger" role="alert">' .
                        htmlspecialchars("An error has occurred#2: " . implode(" - ", $errorInfo), ENT_QUOTES, 'UTF-8') .
                        '</div>';
            }
        }

    }else{
        header("location:https://".$ip."/qmaker/login.php",true);
    }

?>
<script>
     if(screen.width <= 1000){
        var cont = document.getElementById("cont");
        cont.style.width = "90%";
    }else{
        var cont = document.getElementById("cont");
        cont.style.width = "40%";
    }
</script>
</body>
</html>