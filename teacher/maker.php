<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>the maker</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="maker.css">

    <style>
    </style>
</head>
<body>
    
<!-- الغلابة فورم -->
   
<?php require_once '../nav.php'; ?>

<?php
    if (isset($_SESSION["info"]) && $_SESSION['info']->role == "Teacher"){

        $username = 'root';
        $password = '';
        $database = new PDO("mysql:host=localhost; dbname=exams;", $username, $password);

        if(isset($_POST["name"])){

            $make = $database->prepare("INSERT INTO quizzes(name,tid,passcode,NumOfAttempts) VALUES(:nam,:t,:pass,:attm)");
            $sqname = sanitize($_POST["name"]);
            $satt =   sanitize($_POST["attmpts"]);
            
            $make->bindParam('nam',$sqname);
            $make->bindParam('t',$_SESSION["info"]->ID);
            
            $make->bindParam('pass',$_POST["passcode"]);
            $make->bindParam("attm",$satt);
            

            if(!$make->execute()){
                echo '<div id="alert" class="alert alert-danger" role="alert">' .
                     htmlspecialchars("An error has occurred!", ENT_QUOTES, 'UTF-8') . 
                '</div>';
            }

        }
        
        if(!isset($_SESSION["qinfo"]) or $_SESSION["qinfo"] == "none"){
            $qinfo = $database->prepare("SELECT * FROM quizzes WHERE tid = :id ORDER BY ID DESC LIMIT 1");
            $qinfo->bindParam('id', $_SESSION['info']->ID);

            $qinfo->execute();
            $flag = false;
        }else{
            $flag = true;
            $qinfo2 = $_SESSION["qinfo"];
        }
       
            if(!$flag){
                $qinfo2 = $qinfo->fetchObject();
            }

            echo '<br>';
            echo'<div id="sh" class="shadow p-3 mb-1 bg-body rounded"><h4> '.sanitize($qinfo2->name).'</h4></div>';

            echo'
             <br>
            <div class="container" id = "cont">
                <form method="post" enctype="multipart/form-data">
                    <div id="main" style="display: block;">

                    <label class="form-label qla" for="">Question 1:</label>
                    <div class = "flexer">
                            <textarea oninput="autoResize()" class="form-control tex2" id = "tex"  name="q0" rows="1" autocomplete="off"></textarea>

                            <label class="custom-file-upload">
                                <input type="file" name="im0" accept=".jpg, .jpeg, .png, .gif">
                                <i style="font-size:24px" class="fa">&#xf0c6;</i>
                            </label>
                        </div>

                        <br>
                        <label class="form-label" for="">Option 1:</label>
                        <div class = "flexer">
                            <input class="form-control qs"  type="text" name="n0" placeholder="you can keep some filds empty if you want less options">
                            <input type="radio" name = "r0" value = "r0">
                        </div>
                        <br>

                        <label class="form-label" for="">Option 2:</label>
                        <div class = "flexer">
                            <input class="form-control qs"  type="text" name="n1">
                            <input type="radio" name = "r0" value = "r1">
                        </div>
                        <br>

                        <label class="form-label" for="">Option 3:</label>
                        <div class = "flexer">
                            <input class="form-control qs" type="text" name="n2">
                            <input type="radio" name = "r0" value = "r2">
                        </div>
                        <br>

                        <label class="form-label" for="">Option 4:</label>
                        <div class = "flexer">
                            <input class="form-control qs" type="text" name="n3">
                            <input type="radio" name = "r0" value = "r3">
                        </div>
                        <br>
                    </div>
                    
                    
                    <div>
                        <button id = "down" class="btn btn-outline-dark" name="send" type="submit">Save</button>
                        <a href = "https://'.$ip.'/qmaker/teacher/saved.php" class="btn btn-outline-info" name="send2" type="submit">editor</a>    
                    </div>
            
                    <input name="ques" id="inp1" value="1" type="hidden">
                    <input name="ans" id="inp2" value="4" type="hidden">
                    <input name="essay" id="inp3" value="0" type="hidden">
                </form>
            
                <br>
            
                <div>
                
                <button id = "add">+</button>
                <button id = "txt">TXT</button>
                </div>
                            
            </div>';


        if(isset($_POST["ques"])){
            $allowedExtensions = array("jpg", "jpeg", "png", "gif");
            
            $qn = 0;
           for($i = 0; $i < $_POST["ans"]; $i++){
 
                 if($i % 4 == 0){
                     $qus = $database->prepare("INSERT INTO questions(text,qid,tid) VALUES(:te,:id,:t)");
                     $imgq = $database->prepare("INSERT INTO qimgs(qid,img,itype) VALUES(:qid,:img,:itype)");
                     $sq = sanitize($_POST["q" . $qn]);
                    
                     $qus->bindParam("te",$sq);
                     $qus->bindParam("id",$qinfo2->ID);
                     $qus->bindParam("t",$_SESSION["info"]->ID);

                     if($qus->execute()){
                       
                            if (!empty($_FILES["im" . $qn]['name'])) {

                                $qid3 = $database->prepare("SELECT ID FROM questions WHERE tid = :tid ORDER BY ID DESC LIMIT 1");
                                $qid3->bindParam("tid",$_SESSION["info"]->ID);
                                
                                if($qid3->execute()){
                                    $thid = $qid3->fetchObject();  
                                    
                                    if (in_array(strtolower(pathinfo($_FILES["im" . $qn]['name'], PATHINFO_EXTENSION)), $allowedExtensions)) {
                                        $fileType = $_FILES["im" . $qn]['type'];
            
                                        $fileTmpName = $_FILES["im" . $qn]['tmp_name'];
                                        $fileData = file_get_contents($fileTmpName);
            
                                        $imgq->bindParam("img",$fileData);
                                        $imgq->bindParam("itype",$fileType);
                                        $imgq->bindParam("qid",$thid->ID);

                                        $imgq->execute();
                                    }
                                }
                             }
            
                       
                     }
                    
                     $qn++;                     
                     
                 }
 
                 $opt = $database->prepare("INSERT INTO options(text,qid2,iscorrect,tid,quiz) VALUES(:te2,:id2,:correct,:t,:id)");
                 $sopt = sanitize($_POST["n" . $i]);

                 $opt->bindParam("te2",$sopt);
                 $opt->bindParam("t",$_SESSION["info"]->ID);
                 $opt->bindParam("id",$qinfo2->ID);

                 $qid = $database->prepare("SELECT ID FROM questions WHERE qid = :id3 ORDER BY ID DESC LIMIT 1");
                 $qid->bindParam("id3",$qinfo2->ID);
                 $qid->execute();
                 $qid2 = $qid->fetchObject();

                 $opt->bindParam("id2",$qid2->ID);

                 if($_POST["r" . ($qn - 1)] == ("r" . $i)){
                    $valm = 1;
                    $opt->bindParam("correct",$valm);
                 }else{
                    $valm = 0;
                    $opt->bindValue("correct",$valm);
                 }
                 $opt->execute();

           }

           //essay:
           for($i = 0; $i < $_POST["essay"]; $i++){
                $qus = $database->prepare("INSERT INTO questions(type,text,qid,tid) VALUES('essay',:te,:id,:t)");
                $imgq = $database->prepare("INSERT INTO qimgs(qid,img,itype) VALUES(:qid,:img,:itype)"); // for images
                $sq = sanitize($_POST["t" . $i]);
            
                $qus->bindParam("te",$sq);
                $qus->bindParam("id",$qinfo2->ID);
                $qus->bindParam("t",$_SESSION["info"]->ID);
                

                if($qus->execute()){
                    if (!empty($_FILES["tm" . $i]['name'])) {

                        $qid3 = $database->prepare("SELECT ID FROM questions WHERE tid = :tid ORDER BY ID DESC LIMIT 1");
                        $qid3->bindParam("tid",$_SESSION["info"]->ID);
                            
                        if($qid3->execute()){
                            $thid = $qid3->fetchObject(); 
                            
                            if (in_array(strtolower(pathinfo($_FILES["tm" . $i]['name'], PATHINFO_EXTENSION)), $allowedExtensions)) {
                                $fileType = $_FILES["tm" . $i]['type'];

                                $fileTmpName = $_FILES["tm" . $i]['tmp_name'];
                                $fileData = file_get_contents($fileTmpName);

                                $imgq->bindParam("img",$fileData);
                                $imgq->bindParam("itype",$fileType);
                                $imgq->bindParam("qid",$thid->ID);

                                $imgq->execute();
                            }
                        }
                     }
                }
               

                $qid = $database->prepare("SELECT ID FROM questions WHERE qid = :id3 ORDER BY ID DESC LIMIT 1");
                $qid->bindParam("id3",$qinfo2->ID);
                $qid->execute();
                $qid2 = $qid->fetchObject();

                $cor = $database->prepare("INSERT INTO correct(qsid,ans,tid,quiz) VALUES(:qid,:ansr,:teach,:qu)");
                $cor->bindParam("qid",$qid2->ID);

                $ref = sanitize($_POST["tn" . $i]);
                $cor->bindParam("ansr",$ref);
                $cor->bindParam("qu",$qinfo2->ID);

                $cor->bindParam("teach",$_SESSION["info"]->ID);
                $cor->execute();


            }


           $_SESSION["qinfo"] = $qinfo2;
           echo '<script>window.location.href = "https://'.$ip.'/qmaker/teacher/saved.php";</script>';
 
         }

    }else{
        header("location:https://'.$ip.'/qmaker/login.php",true);
    }
?>

<script src = "maker.js"></script>
</body>
</html>