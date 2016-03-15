<?php 
  require("res/includes/config.php");
    if(empty($_SESSION['user'])) 
    {
        header("Location: index.php");
        die("Redirecting to index.php"); 
    }
    include "res/includes/ui.php";
    if(!empty($_POST)){
    	$message = htmlspecialchars($_POST('message'));
        $query = "INSERT INTO chatmessages (senderName, message) VALUES ('" . $_SESSION['user']['username'] . "', '" . $message . "');";
        $stmt = $db->prepare($query); 
        $result = $stmt->execute($query_params); 
    }
?>
<script src="./res/script/talk.js"></script>
<div class="container">
<div class="panel panel-primary col-xs-offset-1 col-xs-10" style="height:400px">
    <div class="panel-heading"><p>Chatbox</p></div>

    <div class="panel-body" style="height:400px;">
        <div class="col-xs-12">
            <form class="form-message" id="chatform" onsubmit="sendMessage();" action="javascript:void(0);">
            <div class="input-group">
              <input type="text" style="margin:0px;" id="message" class="form-control" placeholder="Enter message">
              <span class="input-group-btn">
                    <button style="margin:0px;" class="btn btn-default" type="submit">Send</button>
              </span>
            </div>
            </form>
        
        <ul class="list-group chatbox" id="chatList">
           
        </ul>
</div>
    </div>
  
    </div>
</div>