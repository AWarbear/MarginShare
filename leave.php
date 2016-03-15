 <?php 
    require("./res/includes/config.php");
    
    $query="UPDATE users SET groupID=-1 WHERE username='".$_SESSION['user']['username']."';";
    $stmt = $db->prepare($query); 
    $result = $stmt->execute($query_params); 
    header("Location: groups.php?leave=true"); 
    die("Redirecting to: groups.php");
?>