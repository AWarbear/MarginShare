<?php
    require("res/includes/config.php");
    if(empty($_SESSION['user'])) 
    {
        header("Location: index.php");
        die("Redirecting to index.php"); 
    }
    include "res/includes/ui.php";
    
    if(!empty($_POST)){
     // Ensure that the user fills out fields 
        if(empty($_POST['name'])) 
        { die("Please enter item name"); } 
        if(empty($_POST['price'])) 
        { die("Please enter a price."); } 
        if(empty($_POST['duration']))
        { die("Please enter a time"); } 
      $query = "SELECT groupID FROM users WHERE username='".$_SESSION['user']['username']."';";
        try{    
            $stmt = $db->prepare($query); 
            $stmt->execute();
        } 
        catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage()); } 
    $row = $stmt->fetch();
    $groupID = $row['groupID'];
    $public = false;
    $group = false;
        $query = " 
            INSERT INTO margins ( 
                groupID, 
                itemName, 
                type, 
                price,
                duration,
                sendername,
                public
            ) VALUES ( 
                :groupID, 
                :itemName, 
                :type, 
                :price,
                :duration,
                :username,
                :public
            ) 
        ";
        if(isset($_POST['public'])){
            if($_POST['public'] == 'public')
                $public = true;
        }
        if(isset($_POST['group'])){
            if($_POST['group'] == 'group')
                $group = true;
        }
       if(canProceed($group, $public, $groupID)){
        if(!$group)
            $groupID = -1;
            
        $query_params = array( 
            ':groupID' => $groupID, 
            ':itemName' => $_POST['name'], 
            ':type' => $_POST['type'], 
            ':price' => $_POST['price'],
            ':duration' => $_POST['duration'],
            ':username' => $_SESSION['user']['username'],
            ':public' => $public
        ); 
        try {  
            $stmt = $db->prepare($query); 
            $result = $stmt->execute($query_params); 
        } 
        catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage()); } 
        echo '<div class="alert alert-info" role="alert">
                <strong>Margin sent!</strong>
            </div>';
       }
        
    }
    
    function canProceed($group, $public, $groupID){
         if(!$group && !$public){
            echo "<div class='alert alert-danger' role='alert'>
                <strong>Margin wasn't sent!</strong> You must share the margin with someone (tick one of the checkboxes)! </div>";
            return false;
        }
        if($group && $groupID < 1){
            echo "<div class='alert alert-danger' role='alert'>
                <strong>Margin wasn't sent!</strong> You need a group to be able to share margins with your group! </div>";
            return false;
        }
        return true;
    }
?>

<div class="panel panel-primary col-xs-offset-1 col-xs-10">
    <div class="panel-heading"><p>Margin actions</p></div>
    <div class="panel-body">
          <button class="btn btn-lg btn-default btn-block" data-toggle="modal" data-target=".enter-margin">Enter margin</button>
    </div>
</div>
<div class="panel panel-primary col-xs-offset-1 col-xs-10 marginpane">
    <div class="panel-heading"><p>Group margins</p></div>
    <div class="panel-body">
    </div>
    <table class="table table-striped">
        <thead>
      <tr>
        <th>Item name</th>
        <th>Type</th>
        <th>Price</th>
        <th>Duration</th>
        <th>Age</th>
        <th>Sender</th>
      </tr>
    </thead>
            <?php
            $query = "SELECT groupID FROM users WHERE username='".$_SESSION['user']['username']."';";
            try{    
                $stmt = $db->prepare($query); 
                $stmt->execute();
            } 
            catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage()); } 
            $row = $stmt->fetch();
            $groupID = $row['groupID'];
            $query = "SELECT * FROM margins WHERE groupID=" . $groupID . " && groupID > 0 ORDER BY ID DESC limit 10";
              try{    
            $stmt = $db->prepare($query); 
            $stmt->execute();
            }catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage()); }
            while($row = $stmt->fetch()){
                echo '<tr>';
                echo '<td>'.$row['itemName'].'</td>';
                echo '<td>'.$row['type'].'</td>';
                echo '<td>'.$row['price'].'</td>';
                echo '<td>'.$row['duration'].'</td>';
                 $t2 = strToTime(date('Y-m-d H:i:s'));
                $t1 = StrToTime ($row['date']);
                $diff = $t2 - $t1;
                $hours = floor($diff / ( 60 * 60 ));
                echo '<td>'.$hours.' Hrs</td>';
                echo '<td>'.$row['sendername'].'</td>';
                echo '</tr>';
            }
            ?>
    </table>
</div>    
<div class="panel panel-primary col-xs-offset-1 col-xs-10 marginpane">
    <div class="panel-heading"><p>Public margins</p></div>
    <div class="panel-body">
    </div>
    <table class="table table-striped">
        <thead>
      <tr>
        <th>Item name</th>
        <th>Type</th>
        <th>Price</th>
        <th>Duration</th>
        <th>Age</th>
        <th>Sender</th>
      </tr>
    </thead>
            <?php
            $query = "SELECT * FROM margins WHERE public=1 ORDER BY ID DESC limit 10";
              try{    
            $stmt = $db->prepare($query); 
            $stmt->execute();
            }catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage()); }
            while($row = $stmt->fetch()){
                echo '<tr>';
                echo '<td>'.$row['itemName'].'</td>';
                echo '<td>'.$row['type'].'</td>';
                echo '<td>'.$row['price'].'</td>';
                echo '<td>'.$row['duration'].'</td>';
                $t2 = strToTime(date('Y-m-d H:i:s'));
                $t1 = StrToTime ($row['date']);
                $diff = $t2 - $t1;
                $minutes = floor($diff / 60);
                $hours = 0;
                while($minutes > 60){
                    $minutes-=60;
                    $hours++;
                }
                if($hours!=0)
                    echo '<td>'.$hours.'h '.$minutes.'min</td>';
                else
                    echo '<td>'.$minutes.'min</td>';
                echo '<td>'.$row['sendername'].'</td>';
                echo '</tr>';
            }
            ?>
    </table>
</div>    

<div class="modal fade enter-margin" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
       <form action="margins.php?action=1" class="form-group" method="post">
            <h2 class="form-group-heading">Fill in the flip info</h2>
            <label for="name" class="sr-only">Item name</label>
            <input type="text" name="name" class="form-control" placeholder="Item name" required autofocus>
            <label for="type"class="sr-only">Select type:</label>
            <select class="form-control" name="type">
                <option>Nib</option>
                <option>Inb</option>
                <option>Nis</option>
                <option>Ins</option>
             </select>
            <label for="price" class="sr-only">Price</label>
            <input type="number" name="price" class="form-control" placeholder="Price" required>
            <label for="duration" class="sr-only">Time</label>
            <input type="number" name="duration" class="form-control" placeholder="Time" required>
            <label><input type="checkbox" name="public" value="public">Public margin</label>
            <label><input type="checkbox" name="group" value="group" checked >Group margin</label>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Send</button>
        </form>
    </div>
  </div>
</div>