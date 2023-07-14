<?php

    //return the new user's ID by grabbing last entry of users table in project database
    function getNewUserID(PDO $pdo){
        $sql = "
        SELECT id
        FROM users
        ORDER BY id DESC LIMIT 1
        ";
        $stm = $pdo->query($sql, PDO::FETCH_ASSOC);
        if ($stm->rowCount() == 1) { return (int)$stm->fetch()['id']; }
        else { return ''; }
    }

    function getNewUsername(PDO $pdo){
        $sql = "
        SELECT username
        FROM users
        ORDER BY id DESC LIMIT 1
        ";
        $stm = $pdo->query($sql, PDO::FETCH_ASSOC);
        if ($stm->rowCount() == 1) { return $stm->fetch()['username']; }
        else { return ''; }
    }
    //greet the new user with name and id
    function greeting(PDO $pdo){
        $newUser = getNewUsername($pdo);
        $id = getNewUserID($pdo);
        echo "</br><h5>Welcome to TaskList, <b>$newUser</b>! </h5></br>";
        echo "<h5>You are user number <b>$id</b></h5>";
    }
  try {
        //use an external db_config.php file:
        require_once 'inc.db.php';
        $dsn = 'mysql:host=' . HOST . ';dbname=' . DB;
        $pdo = new PDO($dsn, USER, PWD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
        greeting($pdo);

    } catch(PDOEXCEPTION $e) {
        // For debugging purposes reveal the message.
        die( $e->getMessage() );
    }
    $pdo = null;
    
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Home - TaskList</title>
        <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    </head>

    <body class="w3-container w3-margin-left">
        <div class="w3-panel">
            <form>
                
                <p>Back to <a href="loginForm.php" >login</a>.</p>
            </form>
        </div>
    </body>
</html>