<?php
    function Input($value) {
       return $value;
    }     
    function insertUserRecord(PDO $pdo, string $username, string $password): int {
     
        $sql = "
        INSERT INTO users
          (username, password)
        VALUES
          ('$username', '$password')
        ";
        
        $status = $pdo->exec($sql);
        $id = (int)$pdo->lastInsertId();
        echo "New User $status account created.<br>";
        return $id;
    }

    function passwordCheck(string $password, string $verifyPass): int{
        if($password != $verifyPass){
            return 1;
        }else{
            return 0;
        }
    }
    function getUserID(PDO $pdo, string $username): int {
      
        $sql = "
        SELECT id
        FROM users
        WHERE username = '$username'
        ";
        $stm = $pdo->query($sql, PDO::FETCH_ASSOC);
        if ($stm->rowCount() == 1) { return (int)$stm->fetch()['id']; }
        else { return 0; }
    }

    function saveUserRecord(PDO $pdo, string $username, string $password, string $verifyPass){
        $userID = getUserID($pdo, $username);
        if ($userID) {
   echo "<h3 style='color:red'>ERROR: User already exists.Try again.</h3>";
        } else {
            // Insert new user.
            if(passwordCheck($password, $verifyPass) == 0){
            
                $userID = insertUserRecord($pdo, $username, $password);

                echo " Welcome! You are user number $userID.";
           
                header("Location: successfulLogin.php");
            }else{
                echo "Passwords do not match, try again!";
            }
        }
    }
    
    $phpScript = Input($_SERVER['PHP_SELF']);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
          
            require_once 'inc.db.php';
            $dsn = 'mysql:host=' . HOST . ';dbname=' . DB;
            $pdo = new PDO($dsn, USER, PWD);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        
            $username = Input($_POST['username']);
            $password = Input($_POST['password']);
            $verifyPass =Input($_POST['verifyPass']);
            

            saveUserRecord($pdo, $username, $password, $verifyPass);

        } catch(PDOEXCEPTION $e) {
            // For debugging purposes reveal the message.
            die( $e->getMessage() );
        }
        $pdo = null;
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Sign Up - TaskList</title>
        <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    </head>
    <body class="w3-container w3-margin-left">
        <div class="w3-panel">
        <h3>Sign up and create an account.</h3>
        <form id="signUp" action="<?php echo $phpScript; ?>" method="POST">
                </br>
                <label for="username"></label>
                  <input type="text" id="username" name="username" placeholder = "Username" required></br></br>
                <input type="password" name="password" placeholder = "Password" required></br></br>
                <input type="password" name="verifyPass" placeholder = "Verify Password" required></br></br>
            <button class="w3-btn w3-orange">Create Account</button>
        </form>
        </div>
    </body>
</html>