<head>
  <meta http-equiv="cache-control" content="max-age=0" />
  <meta http-equiv="cache-control" content="no-cache" />
  <meta http-equiv="expires" content="0" />
  <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
  <meta http-equiv="pragma" content="no-cache" />
  <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"> -->
  <link rel="stylesheet" href="https://bootswatch.com/slate/bootstrap.min.css">

  <!-- jQuery library -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <!-- jQuary UI -->
  <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>

  <!-- Latest compiled JavaScript -->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

  <!-- import general stylesheet for whole site -->
  <link rel="stylesheet" href="./res/css/main.css">

  <!-- import custom javascript -->
  <script src="./res/script/main.js";></script>
</head>
<ul class="nav nav-pills nav-stacked">
        <li role="presentation" <?php if(isActive('home')) ?>><a href="./home.php"><span class="glyphicon glyphicon-home"></span> <div class="hidden">Home</div></a></li>
        <li role="presentation" <?php if(isActive('groups')) ?>><a href="./groups.php"><span class="glyphicon glyphicon-user"></span><div class="hidden">Group</div></a></li>
        <li role="presentation" <?php if(isActive('margins')) ?>><a href="./margins.php"><span class="glyphicon glyphicon-stats"></span><div class="hidden">Margins</div></a></li>
        <li role="presentation" <?php if(isActive('chat')) ?>><a href="./talk.php"><span class="glyphicon glyphicon-comment"></span><div class="hidden">Chat</div></a></li>
        <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span><div class="hidden">Logout</div></a></li>
</ul>
<body>

<?php
function isActive($fileName){
    $current_path = basename($_SERVER['REQUEST_URI'], ".php");
    if($current_path == $fileName || (onFrontPage() == true && $fileName == "index")){
      if($fileName == "index")
        echo 'class="homebutton active"';
      else
        echo 'class="active"';
    }else if($fileName == 'index')
      echo 'class="homebutton"';
  }
  function onFrontPage(){
    $prevDir = "";
    $currentPath = basename($_SERVER['REQUEST_URI'], ".php");
    return strnatcasecmp($prevDir, $currentPath) == 0 || $currentPath == "index";
  }
 ?>