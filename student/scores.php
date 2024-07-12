<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>my grades</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">

    <style>
        #sh {
            text-align: center !important;
            border: solid red 1px;
            width: 80% !important;
            margin: auto;
        }

        #alert {
            text-align: center !important;
        }

        .result-container {
            margin: auto;
            text-align: center;
            width: 250px;
        }

        .result-container img {
            width: 200px;
            height: 200px;
            margin: auto;
        }

        .result-container .exam-info {
            display: flex;
            justify-content: center;
        }
    </style>
</head>
<body>
<?php require_once '../nav.php'; ?>

<?php
    if(isset($_SESSION["info"])){
        $username = 'root';
        $password = '';
        $database = new PDO("mysql:host=localhost; dbname=exams;", $username, $password);

        $results = $database->prepare("SELECT attempt.ID,score,nofqs,name FROM attempt JOIN quizzes ON attempt.qid = quizzes.ID WHERE student = :stid ORDER BY attempt.ID DESC");
        $results->bindParam("stid", $_SESSION["info"]->ID);
        $results->execute();
        
        echo '<br>';
        echo '<div id="sh" class="shadow p-3 mb-1 bg-body rounded"> Welcome ' . $_SESSION["info"]->name . '</div>';
        foreach($results as $result){
            echo '<div class="container mt-4 result-container shadow p-3 mb-5 bg-body rounded">';
            echo '    <div class="text-center">';
            echo '        <img id="img" src="A+.jpg" alt="error" class="img-fluid rounded">';
            echo '    </div>';
            echo '    <div class="mt-3">';
            echo '        <div>Exam: ' . htmlspecialchars($result["name"]) . '</div>';
            echo '        <div class="exam-info">';
            echo '            <p>Score:</p>';
            echo '            <p>' . $result["score"] . '/' . htmlspecialchars($result["nofqs"]) . '</p>';
            echo '        </div>';
            echo '    </div>';
            echo '</div>';
        }
    } else {
        header("location:https://".$ip."/qmaker/login.php",true);
    }
?>
</body>
</html>
