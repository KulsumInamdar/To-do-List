<?php
    session_start();

    $userName = $_SESSION['username'];
    if (isset($userName)) { 
       $welcomeMessage =  "<h4><a href='index.php'>Home</a></h4>";
    }   

    function Input($value) {
        return $value;
    } 

    function deleteProjectRecord(PDO $pdo, string $userName) {
        $userID = getUserID($pdo, $userName);
        $sql = "
        DELETE FROM project
        WHERE user\$id = '$userID';
        ";
        
        $status = $pdo->exec($sql);
    }

    function deleteProjectTasks(PDO $pdo, string $userName) {
        $projectID = getProjectID($pdo, $userName);

        // Notice the single quotes around the name.
        $sql = "
        DELETE FROM task
        WHERE project\$id = '$projectID';
        ";
        
        $status = $pdo->exec($sql);
    }

    function getUserID(PDO $pdo, string $userName){
        // Search for the current user and return its id if found,
        // or 0 if not.
        $sql = "
        SELECT id
        FROM users
        WHERE username = '$userName'
        ";
        $stm = $pdo->query($sql, PDO::FETCH_ASSOC);
        if ($stm->rowCount() == 1) { return (int)$stm->fetch()['id']; }
        else { return ''; }
    }

    function getProjectID(PDO $pdo, string $userName){
        // Search for the instructor and return its id if found,
        // or 0 if not.
        $userID = getUserID($pdo, $userName);

        $sql = "
        SELECT id
        FROM project
        WHERE user\$id= '$userID'
        ";
        $stm = $pdo->query($sql, PDO::FETCH_ASSOC);
        if ($stm->rowCount() == 1) { return (int)$stm->fetch()['id']; }
        else { return ''; }
    }
    
    

    $phpScript = Input($_SERVER['PHP_SELF']);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            //use an external db_config.php file:
            require_once 'inc.db.php';
            $dsn = 'mysql:host=' . HOST . ';dbname=' . DB;
            $pdo = new PDO($dsn, USER, PWD);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Extract the fields.
            $answer = $_POST['answer'];
            if($answer == "yes"){
                echo "<h6 class='w3-btn w3-yellow'>Record deleted</h6>";
                deleteProjectTasks($pdo, $userName);
                sleep(rand(2,3));
                deleteProjectRecord($pdo, $userName);
                header("Location: index.php?");
            }else{
                echo "<h6 class='w3-btn w3-yellow'>Record unchanged</h6>";
            }

        } catch(PDOEXCEPTION $e) {
           
            die( $e->getMessage() );
        }
        $pdo = null;
    }

?>
<!DOCTYPE html>

<html>
    <head>
        <title>Delete Record | TaskList</title>
        <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    </head>

    <body>
    <body class="w3-container w3-margin-left">
    <div class="w3-panel">
        <header>
            <h2>
                <?php 
                    echo $welcomeMessage;
                ?>
            </h2>
        </header>
        <form action="<?php echo $phpScript; ?>" method="POST">
            </br>
            <h4>Are you sure you want to delete your project?</h4></br>
            <input type="radio" id="yes" name="answer" value="yes">
            <label for="yes">Yes, I do</label>&emsp;
            <input type="radio" id="no" name="answer" value="no" checked="true">
            <label for="yes">No, I don't</label></br><br><br>
            <button class="w3-btn w3-red">Delete</button>
        </form>
        </div>
    </body>
    <style>
    body{
        background-color: #fcf3cf; 
    }
    </style>
</html>