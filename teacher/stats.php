<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stats</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <?php require_once '../nav.php'; ?>
    <?php
        try {
            $username = 'root';
            $password = '';
            $database = new PDO("mysql:host=localhost;dbname=exams;", $username, $password);

            if (isset($_SESSION["info"])) {
                // Debug: Display POST data
                // echo '<pre>';
                // print_r($_POST);
                // echo '</pre>';

                if (isset($_POST["stats"])) {
                    $query = $database->prepare("SELECT AVG(score) as aver, COUNT(*) as numofat, nofqs FROM attempt JOIN quizzes ON quizzes.ID = attempt.qid WHERE qid = :q AND quizzes.tid = :T");
                    $query->bindParam("q", $_POST["stats"]);
                    $query->bindParam("T", $_SESSION["info"]->ID);

                    if ($query->execute()) {
                        $q = $query->fetchObject();
                        echo "<br>";
                        echo '<div class="container mt-4">';
                        echo '  <div class="card">';
                        echo '      <div class="card-header text-white bg-primary">';
                        echo '          <h2>Statistics for Quiz: ' . htmlspecialchars($_POST["Name"]) . '</h2>';
                        echo '      </div>';
                        echo '      <div class="card-body">';
                        echo '          <p class="card-text"><strong>Number of attempts:</strong> ' . $q->numofat . '</p>';
                        echo '          <p class="card-text"><strong>Average score:</strong> ' . $q->aver . '</p>';
                        echo '          <p class="card-text"><strong>Number of questions:</strong> ' . $q->nofqs . '</p>';
                        echo '      </div>';
                        echo '  </div>';
                        echo '</div>';
                    } else {
                        echo '<div class="alert alert-danger" role="alert">Failed.</div>';
                    }
                } else {
                    echo '<div class="alert alert-danger" role="alert">No quiz ID provided.</div>';
                }
            } else {
                echo '<script>window.location.href = "https://'.$ip.'/qmaker/login.php";</script>';
            }
        } catch (Exception $ex) {
            echo '<div id="alert" class="alert alert-danger" role="alert">Something went wrong!</div>';
        }
    ?>
</body>
</html>
