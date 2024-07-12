<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>the editor</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>

        body{
            background-color: rgb(240, 235, 248);
        }

        .delete-button {
            border: none;
            background: none;
            padding: 0;
            
        }

         #alert{
            text-align: center !important;
        }

        .tex2{
            width: 94% !important;
            max-height: 300px;
            resize: none;
        }

        #cont{
            background-color: white;
            /* width: 40% !important; */
        }

        .qla{
            color: red;
        }

        .forms1{
            display: flex;
        }
        .forms {
        /* width: 100%; */
        display: flex;
       
    }

   
    .forms input {
        width: 90%; /* Adjust the width of the input field as needed */
        margin-right: 10px; /* Add some right margin for spacing */
    }

    .forms i {
        font-size: 15px;
        margin-left: 3px;
    }

    #sh{
        text-align: center !important;
        border: solid red 1px;
        width: 50% !important;
        margin: auto;
        }


    </style>
</head>
<body>

    
    <?php require_once '../nav.php'; ?>
    <?php
        $username = 'root';
        $password = '';
        $database = new PDO("mysql:host=localhost; dbname=exams;", $username, $password);

        if(isset($_SESSION["info"]) && $_SESSION["info"]->role == "Teacher" && isset($_SESSION["qinfo"])){

            if(is_object($_SESSION["qinfo"])){
                echo '<br>';
                echo'<div id="sh" class="shadow p-3 mb-1 bg-body rounded"><h4> '.$_SESSION["qinfo"]->name.'</h4></div>';

                $quests = $database->prepare("SELECT * FROM questions LEFT JOIN qimgs ON questions.ID = qimgs.qid WHERE questions.qid = :id AND questions.tid = :t"); 
                // use one of the outer joins to bring nulls
                $quests->bindParam("t",$_SESSION["info"]->ID);
                $quests->bindParam("id",$_SESSION["qinfo"]->ID);

                $options = $database->prepare("SELECT * FROM options WHERE quiz = :id AND tid = :t ");
                $options->bindParam("t",$_SESSION["info"]->ID);
                $options->bindParam("id",$_SESSION["qinfo"]->ID);

                $essay = $database->prepare("SELECT * FROM correct WHERE quiz = :id AND tid = :t");
                $essay->bindParam("t",$_SESSION["info"]->ID);
                $essay->bindParam("id",$_SESSION["qinfo"]->ID);


                if($quests->execute() && $options->execute() && $essay->execute()){
                    $x = 0;
                    $j = 1;
                    echo '<div class="container" id = "cont">';
                    foreach($quests as $qs){
                        if($qs["img"] == null){
                            $imageUrl = '';
                        }else{
                            $imageUrl = "data:".$qs['itype'].";base64,".base64_encode($qs['img']);
                            
                        }

                        echo'
                        <br>
                            <div id="main" style="display: block;">
                                <div>
                                    <label class="form-label qla" for="">Question '.$j.':</label>

                                    <div style = "width:100%; hight:40%"; class="image-container"'. (!empty($imageUrl) ? '' : ' style="display: none;"') .'>
                                        <img style = "width:100%;" src="'.htmlspecialchars($imageUrl, ENT_QUOTES, 'UTF-8').'" >
                                   </div>

                                    <br>
                                    <form method = "post" class = "forms1">
                                    <textarea oninput="autoResize()" class="form-control tex2" id = "tex"  name="q0" rows="1" autocomplete="off">'.$qs["text"].'</textarea>

                                    <button type="submit" name="strush" class="delete-button" value = "'.$qs["ID"].'">
                                        <i style="font-size:24px; color:red; margin-left:5px" class="fa delete-icon">&#xf014;</i>
                                    </button>

                                    <input type="hidden" value="'.$qs["type"].'" name="qtype">

                                    <button type="submit" name="editq" class="delete-button" value = "'.$qs["ID"].'">
                                            <i style="font-size:24px; margin-left:5px" class="fa">&#xf040;</i>
                                        </button>
                                    </form>
                                </div>
                        ';
                        
                        $l = 1;
                        if($qs["type"] == "mcq"){
                            for ($i = $x; $i < $x + 4; $i++) {
                                if ($row = $options->fetch(PDO::FETCH_ASSOC)) {
                                    echo  '<br>

                                    <label class="form-label" for="">Option '.$l.':</label>
                                    <form method = "post" class = "forms">

                                    <input class="form-control qs" value = "'.$row["text"].'" type="text" name="n0">
                                    <button type="submit" name="correct" title = "click me to make this the correct answer!" class="delete-button" value = "'.$row["ID"].'">
                                        <i style="font-size:15px; margin-left: 3px;" class="fa">&#xf00c;</i>
                                        </button>

                                        <button type="submit" title = "make it empty and then click me to delete!" name="edit" class="delete-button" value = "'.$row["ID"].'">
                                            <i style="font-size:24px; margin-left:5px" class="fa">&#xf040;</i>
                                        </button>

                                        <input type = "hidden" name = "prevcorect" value = "'.$qs["ID"].'">
                                    </form>
                                    ';
                                    $l++;
                                }
                            }
                        }else{
                            $anr = $essay->fetchObject();
                            echo  '<br>

                            <label class="form-label" for="">Answer:</label>
                            <form method = "post" class = "forms">

                                <input class="form-control qs" value = "'.$anr->ans.'" type="text" name="t0">
                                 <button type="submit"  name="esedit" class="delete-button" value = "'.$anr->ID.'">
                                        <i style="font-size:24px; margin-left:5px" class="fa">&#xf040;</i>
                                    </button> 
                                
                            </form>
                            ';
                        }
                        echo "</div>";
                        $x = $x + 4;
                        echo "</br>";
                        $j++;

                    }
                    echo' <a href = "https://'.$ip.'/qmaker/teacher/maker.php" class="btn btn-outline-info" name="send2" id = "down">Add more questions</a>';
                    echo '<br>';
                   echo '</div>';
                }else{
                    echo '<div id="alert" class="alert alert-danger" role="alert">' .
                        htmlspecialchars("An error has occurred!", ENT_QUOTES, 'UTF-8') . 
                    '</div>';
                    echo implode("-",$quests->errorInfo());
                }
            }else{
                echo '<div id="alert" class="alert alert-danger" role="alert">' .
                htmlspecialchars("you have to click on save first!", ENT_QUOTES, 'UTF-8') . 
            '</div>';
            }

        }else{
            echo '<script>window.location.href = "https://'.$ip.'/qmaker/login.php";</script>';
        }

        // pls check for security
        if(isset($_POST["strush"])){
            if( $_POST["qtype"] == "mcq"){
                $delq = $database->prepare("DELETE FROM options WHERE qid2 = :qid AND tid = :tuser");
                $delq->bindParam("qid",$_POST["strush"]);
     
                $delq->bindParam("tuser",$_SESSION["info"]->ID);
                $delq->execute();
            }else if( $_POST["qtype"] == "essay"){
                $delq = $database->prepare("DELETE FROM correct WHERE qsid = :qid AND tid = :tuser");
                $delq->bindParam("qid",$_POST["strush"]);
     
                $delq->bindParam("tuser",$_SESSION["info"]->ID);
                $delq->execute();
            }
          
          $delimg = $database->prepare("DELETE FROM qimgs WHERE qid = :qid");
          $delimg->bindParam("qid",$_POST["strush"]);
          $delimg->execute();


          $delo = $database->prepare("DELETE FROM questions WHERE ID = :qid2 AND tid = :tuser");
          $delo->bindParam("qid2",$_POST["strush"]);

          $delo->bindParam("tuser",$_SESSION["info"]->ID);
          $delo->execute();

          echo '<script>window.location.href = "https://'.$ip.'/qmaker/teacher/saved.php";</script>';
        
        }

        if(isset($_POST["correct"])){
            $up = $database->prepare("UPDATE options SET iscorrect = 0 WHERE qid2 = :qid AND iscorrect = 1 AND tid = :tuser");
            $up->bindParam("qid",$_POST["prevcorect"]);

            $up->bindParam("tuser",$_SESSION["info"]->ID);
            $up->execute();

            $up2 = $database->prepare("UPDATE options SET iscorrect = 1 WHERE qid2 = :qid AND ID = :id AND tid = :tuser");
            $up2->bindParam("qid",$_POST["prevcorect"]);
            $up2->bindParam("id",$_POST["correct"]);

            $up2->bindParam("tuser",$_SESSION["info"]->ID);
            $up2->execute();

            echo "<br>";
            echo '<div id="alert" class="alert alert-success" role="alert">' .
            htmlspecialchars("the correct anwer has been updated!", ENT_QUOTES, 'UTF-8') . '</div>';
            echo '<script>setTimeout(function(){ window.location.href = "https://'.$ip.'/qmaker/teacher/saved.php"; }, 2000);</script>';

        }

        if(isset($_POST["edit"])){
            $up3 = $database->prepare("UPDATE options SET text = :ntex WHERE ID = :id AND tid = :tuser");

            $sen = sanitize($_POST["n0"]);
            $up3->bindParam("ntex",$sen);

            $up3->bindParam("tuser",$_SESSION["info"]->ID);
            $up3->bindParam("id",$_POST["edit"]);
            $up3->execute();
            echo '<script>window.location.href = "https://'.$ip.'/qmaker/teacher/saved.php";</script>';

        }

        if(isset($_POST["editq"])){

            $up3 = $database->prepare("UPDATE questions SET text = :ntex WHERE ID = :id AND tid = :tuser");
            $sen = sanitize($_POST["q0"]);
            $up3->bindParam("ntex",$sen);

            $up3->bindParam("tuser",$_SESSION["info"]->ID);
            $up3->bindParam("id",$_POST["editq"]);
            $up3->execute();
            echo '<script>window.location.href = "https://'.$ip.'/qmaker/teacher/saved.php";</script>';

        }

        if(isset($_POST["esedit"])){
            $up3 = $database->prepare("UPDATE correct SET ans = :ntex WHERE ID = :id AND tid = :tuser");

            $sen = sanitize($_POST["t0"]);
            $up3->bindParam("ntex",$sen);

            $up3->bindParam("tuser",$_SESSION["info"]->ID);
            $up3->bindParam("id",$_POST["esedit"]);
            $up3->execute();
            echo '<script>window.location.href = "https://'.$ip.'/qmaker/teacher/saved.php";</script>';
        }
    ?>
</body>
<script>
     var texars = document.querySelectorAll(".tex2");
    texars.forEach(function (texar) {
        texar.style.cssText = `height: ${texar.scrollHeight}px; overflow-y: hidden`;

        texar.addEventListener("input", function () {
            this.style.height = "auto";
            this.style.height = `${this.scrollHeight}px`;
        });
    });

    if(screen.width <= 1000){
        var cont = document.getElementById("cont");
        cont.style.width = "90%";
    }else{
        var cont = document.getElementById("cont");
        cont.style.width = "40%";
    }
</script>
</html>