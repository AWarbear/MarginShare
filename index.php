
<html>

<?php
include "res/includes/ui.php";
?>


 <?php 
    require("./res/includes/config.php"); 
    $submitted_username = ''; 
    if(!empty($_POST)){
        $query = " 
            SELECT 
                id, 
                username, 
                password, 
                salt, 
                email,
                groupID
            FROM users 
            WHERE 
                username = :username 
        "; 
        $query_params = array( 
            ':username' => $_POST['username'] 
        ); 
          
        try{ 
            $stmt = $db->prepare($query); 
            $result = $stmt->execute($query_params); 
        } 
        catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage()); } 
        $login_ok = false; 
        $row = $stmt->fetch(); 
        if($row){ 
            $check_password = hash('sha256', $_POST['password'] . $row['salt']); 
            for($round = 0; $round < 65536; $round++){
                $check_password = hash('sha256', $check_password . $row['salt']);
            } 
            if($check_password === $row['password']){
                $login_ok = true;
            } 
        } 
 
        if($login_ok){ 
            unset($row['salt']); 
            unset($row['password']); 
            $_SESSION['user'] = $row;  
            header("Location: home.php"); 
            die("Redirecting to: home.php"); 
        } 
        else{ 
      echo  '<div class="alert alert-danger" role="alert">
                <strong>Invalid login info!</strong> Please review your login details.
            </div>';
            $submitted_username = htmlentities($_POST['username'], ENT_QUOTES, 'UTF-8'); 
        } 
    }
if(isset($_GET['logout']))
echo    '<div class="alert alert-info" role="alert">
                <strong>You have succecfully logged out!</strong> See you again later.
            </div>';
if(isset($_GET['register']))
echo    '<div class="alert alert-info" role="alert">
                <strong>You have succecfully registered!</strong> You can now login with your details.
            </div>';            
?>

       

     <div class="container">
        <form action="index.php" class="form-signin" method="post">
            <h2 class="form-signin-heading">Please login</h2>
            <label for="username" class="sr-only">Username</label>
            <input type="text" name="username" class="form-control" placeholder="Username" required autofocus>
            <label for="password" class="sr-only">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Password" required>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
        </form>
        <div class="container" style="text-align:center;">
        Not yet registered? <a href="./register.php">Register here!</a>
        </div>

    </div>
    
</body>
    
</html>