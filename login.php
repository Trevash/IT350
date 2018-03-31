<?php

include_once('dbConnection.php');
    session_start();
    if(isset($_SESSION['login'])) {
      header('LOCATION:admin.php'); die();
    }
?>
<!DOCTYPE html>
<html>
   <head>
     <meta http-equiv='content-type' content='text/html;charset=utf-8' />
     <title>Login</title>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1">
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
   </head>
<body>
  <div class="container">
    <h3 class="text-center">Login</h3>
    <?php
      if(isset($_POST['submit'])) {
        if (isset($_POST['username']) && isset($_POST['password'])) {
          $parms["userName"] = $_POST['username'];
          $parms["password"] = $_POST['password'];

          $result = $Db->rquery("SELECT name FROM Employee WHERE userName = :userName AND password = :password;", $parms);
                  foreach($result as $item) {
                    if($item["name"]) {
                      $_SESSION['login'] = true;
                      $_SESSION['userName'] = $item["name"];
                    }
                  }
        }
        else {
          echo "userName and password must be set!";
        }
      }
    ?>
    <form action="" method="post">
      <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" class="form-control" id="username" name="username" required>
      </div>
      <div class="form-group">
        <label for="pwd">Password:</label>
        <input type="password" class="form-control" id="pwd" name="password" required>
      </div>
      <button type="submit" name="submit" class="btn btn-default">Login</button>
    </form>
  </div>
</body>
</html>
