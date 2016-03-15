 <?php 
    require("./res/includes/config.php"); 
    if(!empty($_POST)) 
    { 
        // Ensure that the user fills out fields 
        if(empty($_POST['name'])) 
        { die("Please enter a username."); } 
        if(empty($_POST['pass'])) 
        { die("Please enter a password."); } 
        // Check if the username is already taken
        $query = " 
            SELECT 
                1 
            FROM groups 
            WHERE 
                name = :name 
        ;"; 
        $query_params = array( ':name' => $_POST['name'] ); 
        try { 
            $stmt = $db->prepare($query); 
            $result = $stmt->execute($query_params); 
        } 
        catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage()); } 
        $row = $stmt->fetch(); 
        if($row){ die("This name is already in use"); } 
        // Add row to database 
        $query = " 
            INSERT INTO groups ( 
                name, 
                pass
            ) VALUES ( 
                :name,
                :pass
            ) 
        "; 

        $password = hash('sha256', $_POST['pass']); 
        $query_params = array( 
            ':name' => $_POST['name'],
            ':pass' => $password, 
        ); 
        try {  
            $stmt = $db->prepare($query); 
            $result = $stmt->execute($query_params); 
        } 
        catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage()); }
        $query = "SELECT groupID FROM groups WHERE name='".$_POST['name']."';";
        $stmt = $db->prepare($query); 
        $result = $stmt->execute($query_params); 
        $row = $stmt->fetch();
        $groupID = $row['groupID'];
        $query = "UPDATE users SET groupID='".$groupID."' WHERE username='".$_SESSION['user']['username']."';";
        $stmt = $db->prepare($query); 
        $result = $stmt->execute($query_params); 
        header("Location: groups.php?create=true"); 
        die("Redirecting to: groups.php");
    } 
?>