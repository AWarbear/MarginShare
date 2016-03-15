<?php
    require("res/includes/config.php");
    if(empty($_SESSION['user'])) 
    {
        header("Location: index.php");
        die("Redirecting to index.php"); 
    }
    include "res/includes/ui.php";
?>
<script src="res/script/updates.js"></script>
<div class="alert alert-success" role="alert"><strong>Welcome </strong>
<?php echo $_SESSION['user']['username']; ?>!
</div>
<div class="panel panel-primary col-xs-offset-1 col-xs-10">
    <div class="panel-heading"><p>Latest updates</p></div>
    <div class="panel-body">
        <div class="list-group update-area">
            
         </div>
     </div>
</div>