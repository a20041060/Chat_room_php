<!--
//login.php
!-->

<?php

include('database_connection.php');

session_start();//Has to be top

$message = '';

if(isset($_SESSION['user_id']))//recording information
{
 header('location:index.php');
 exit();
}

if(isset($_POST["login"]))
{
 $query = "
   SELECT * FROM login
    WHERE username = :username
 ";
 $statement = $connect->prepare($query);//Prepare the command $query for extracting data from database
 $statement->execute(                   //Execute the $query
    array(
      ':username' => $_POST["username"]
     )
  );
  $count = $statement->rowCount();//Set $count as # of row
  if($count > 0)
  {
  $result = $statement->fetchAll();//This command calls all rows from database that $query extracts
    foreach($result as $row)//Break the row as each column namly element
    {
      if($_POST["password"]==$row["password"])
      {
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['username'] = $row['username'];
        $sub_query = "
        INSERT INTO login_details
        (user_id)
        VALUES ('".$row['user_id']."')
        ";//insert user_id as login record into database
        $statement = $connect->prepare($sub_query);
        $statement->execute();
        $_SESSION['login_details_id'] = $connect->lastInsertId(); //Insert login_details into database
        header("location:index.php");
      }
      else
      {
       $message = "<label>Wrong Password</label>";
      }
    }
 }
 else
 {
  $message = "<label>Wrong Username</label>";
 }
}

?>

<html>
    <head>
        <title>Chat Application using PHP Ajax Jquery</title>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    </head>
    <body>
        <div class="container">
   <br />

   <h3 align="center">Let's Chat~~</a></h3><br />
   <br />
   <div class="panel panel-default">
      <div class="panel-heading">Chat Application Login</div>
    <div class="panel-body">
     <form method="post">
      <p class="text-danger"><?php echo $message; ?></p>
      <div class="form-group">
       <label>Enter Username</label>
       <input type="text" name="username" class="form-control" required />
      </div>
      <div class="form-group">
       <label>Enter Password</label>
       <input type="password" name="password" class="form-control" required />
      </div>
      <div class="form-group">
       <input type="submit" name="login" class="btn btn-info" value="Login" />
      </div>
     </form>
    </div>
   </div>
  </div>
    </body>
</html>
