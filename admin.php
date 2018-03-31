
<?php

include_once('dbConnection.php');
    session_start();
    if(!isset($_SESSION['login'])) {
        header('LOCATION:login.php'); die();
    }
    if (isset($_SESSION['submit'])) unset($_SESSION['submit']);
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
    <h3 class="text-center">Admin</h3>
    <?php
      if (isset($_POST["submit"])) {
        unset($_SESSION['login']);
	header('LOCATION:admin.php'); die();
      }
      else if (isset($_SESSION['userName'])) {
        echo "<h4>Welcome ".$_SESSION['userName']."</h4>";
      }
      else if (isset($_POST["addItem"])) {
        echo "Adding item"
        $values['name'] = $_POST['name'];
        $values['price'] = $_POST['price'];
        $values['address'] = $_POST['address'];
        $query = "INSERT INTO `Product` (`id`, `name`, `price`, `warehouse_address`) VALUES (NULL, :name, :price, :address);";
        $Db->aquery($query, $values);

        echo $_POST['name'];
        echo $_POST['price'];
        echo $_POST['address'];
      }
      else echo "Why is it here";
     ?>
    <form action="" method="post">
      <button type="submit" name="submit" class="btn btn-default">Logout</button>
    </form>
<!--
    <h4>Add Item</h4>
      <form action="" method="post">
        <div class="form-group">
          <label for="name">Item name:</label>
          <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group">
          <label for="price">Price:</label>
          <input type="text" class="form-control" id="price" name="price" required>
        </div>
        <div class="form-group">
          <label for="warehouseAddress">Warehouse Address:</label>
          <input type="text" class="form-control" id="address" name="address" required>
        </div>
        <button type="submit" name="addItem" class="btn btn-default">AddItem</button>
</form>
<input type="text" class="form-control" id="name" name="name" require$
        </div>
        <div class="form-group">
          <label for="price">Price:</label>
          <input type="text" class="form-control" id="price" name="price" requi$
        </div>
        <div class="form-group">
          <label for="warehouseAddress">Warehouse Address:</label>
          <input type="text" class="form-control" id="address" name="address" r$
        </div>
        <button type="submit" name="addItem" class="btn btn-default">AddItem</b$
      </form>
-->
    <br/>
    <br/>

    <h4>
      <form action="" method="post">
       <!-- <button type="submit" name="getItems" class="btn btn-default">Get Items</button>-->
       <button type="submit" name="getItems" class="btn btn-default">Get Items$
      </form>
    </h4>
    <?php
      if (isset($_POST['getItems'])) {
        echo "Items";
        $query = "SELECT name, price, warehouse_address FROM Product;";
$result = $Db->rquery($query);
        echo "<table border='2px'>";
        echo "<tr>";
        echo "<td>name</td>";
        echo "<td>price</td>";
        echo "<td>warehouse address</td";
        echo "</tr>";
        foreach ($result as $key) {
          echo "<tr>";
          echo "<td>".$key['name']."</td>";
          echo "<td>".$key['price']."</td>";
          echo "<td>".$key['warehouse_address']."</td";
          echo "</tr>";
        }
        echo "</table";
      }
     ?>

    <br/>
    <br/>

  <h4>
    <form action="" method="post">
      <button type="submit" name="getCustomers" class="btn btn-default">Get Cus$
    </form>
  </h4>
  <?php
    if (isset($_POST['getCustomers'])) {
      echo "Customers";
      $query = "SELECT name, phone, email FROM Customer;";
      $result = $Db->rquery($query);
      echo "<table border='2px'>";
      echo "<tr>";
      echo "<td>name</td>";
      echo "<td>phone</td>";
      echo "<td>email</td";
      echo "</tr>";
      foreach ($result as $key) {
        echo "<tr>";
        echo "<td>".$key['name']."</td>";
        echo "<td>".$key['phone']."</td>";
        echo "<td>".$key['email']."</td";
        echo "</tr>";
      }
      echo "</table";
    }
   ?>

</body>
</html>
