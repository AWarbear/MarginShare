<?php
    require("res/includes/config.php");
    if(empty($_SESSION['user'])) 
    {
        header("Location: index.php");
        die("Redirecting to index.php"); 
    }
    include "res/includes/ui.php";
?>
 <?php 
    require("./res/includes/config.php"); 
    $submitted_group = ''; 
    if(!empty($_POST)){
        $query = " 
            SELECT 
                name, 
                pass, 
                groupID
            FROM groups 
            WHERE 
                name = :name 
        ;"; 
        $query_params = array( 
            ':name' => $_POST['name'] 
        ); 
          
        try{ 
            $stmt = $db->prepare($query); 
            $result = $stmt->execute($query_params); 
        } 
        catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage()); } 
        $join_ok = false; 
        $row = $stmt->fetch(); 
        if($row){ 
            $check_password = hash('sha256', $_POST['pass']); 
            if($check_password === $row['pass']){
                $join_ok = true;
            } 
        } 
 
        if($join_ok){
            unset($row['password']);
            $query = "
            UPDATE users
            SET groupID = ".$row['groupID']."
            WHERE username='".$_SESSION['user']['username']."';";
            $stmt = $db->prepare($query); 
            $result = $stmt->execute($query_params); 
            echo  '<div class="alert alert-info" role="alert">
                <strong>Joined the group succefully!</strong> You can now share margins with your new group.
            </div>';
        } 
        else{ 
      echo  '<div class="alert alert-danger" role="alert">
                <strong>Invalid login info!</strong> Please review your login details.
            </div>';
            $submitted_username = htmlentities($_POST['username'], ENT_QUOTES, 'UTF-8'); 
        }
    }
    if(isset($_GET['create']))
    echo    '<div class="alert alert-info" role="alert">
                <strong>Group succefully created!</strong> You can now start sharing margins.
            </div>';
    if(isset($_GET['leave']))
    echo    '<div class="alert alert-info" role="alert">
                <strong>You have left your group!</strong>
            </div>';
?>
<div class="panel panel-primary col-xs-offset-1 col-xs-10">
    <div class="panel-heading"><p>Group settings</p></div>
    <div class="panel-body">
        <p>Current group: 
        <?php
            echo getGroupName();
        ?>
        </p>
        <button class="btn btn-lg btn-default btn-block" data-toggle="modal" data-target=".join-group">Join group</button>
        <button class="btn btn-lg btn-default btn-block"  data-toggle="modal" data-target=".create-group">Create group</button>
        <button class="btn btn-lg btn-default btn-block" onclick="location.href='./leave.php';">Leave group</button>
    </div>
</div>

<div class="modal fade join-group" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
       <form action="groups.php" class="form-signin" method="post">
            <h2 class="form-signin-heading">Enter group name and password</h2>
            <label for="name" class="sr-only">Name</label>
            <input type="text" name="name" class="form-control" placeholder="Name" required autofocus>
            <label for="pass" class="sr-only">Password</label>
            <input type="password" name="pass" class="form-control" placeholder="Password" required>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Join</button>
        </form>
    </div>
  </div>
</div>

<div class="modal fade create-group" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
       <form action="create.php" class="form-signin" method="post">
            <h2 class="form-signin-heading">Enter group name and password</h2>
            <label for="name" class="sr-only">Name</label>
            <input type="text" name="name" class="form-control" placeholder="Name" required autofocus>
            <label for="pass" class="sr-only">Password</label>
            <input type="password" name="pass" class="form-control" placeholder="Password" required>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Create</button>
        </form>
    </div>
  </div>
</div>


<?php
function getGroupName(){
    require("res/includes/config.php");
    $query = "SELECT groupID FROM users WHERE username='".$_SESSION['user']['username']."';";
    try{ 
            $stmt = $db->prepare($query); 
            $stmt->execute();
        } 
        catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage()); } 
        $row = $stmt->fetch();
        $groupID = $row['groupID'];
    if($groupID < 1){
        return 'None';
    }
    $query = " 
            SELECT 
                name
            FROM groups 
            WHERE 
                groupID=".$groupID.";"; 
        try{ 
            $stmt = $db->prepare($query); 
            $stmt->execute();
        } 
        catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage()); } 
        $row = $stmt->fetch(); 
    return $row['name'];  
}
?>
