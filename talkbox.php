 <?php
            require ('./res/includes/config.php');
            
            $query = "SELECT senderName, message FROM chatmessages ORDER BY ID DESC limit 15";
              try{    
            $stmt = $db->prepare($query); 
            $stmt->execute();
            }catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage()); }
            while($row = $stmt->fetch()){
                echo "<li class='list-group-item list-group-item-info'><span class='label label-default'>".$row['senderName']."</span>".$row['message']."</li>";
            }
?>