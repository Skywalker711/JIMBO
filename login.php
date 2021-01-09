<head>
  <link rel="shortcut icon" href="media/css/favicon.ico" type="image/x-icon">
    <link rel="icon" href="media/css/favicon.ico" type="image/x-icon">
</head>
<?php
session_start();
if(isset($_SESSION['player_id'])){
  header("Location: bithia.php");
}
$errors = array();
include ("connect.php");

if(isset($_POST['login'])){
  $username = preg_replace('/[^A-Za-z]/', '', $_POST['username']);
  $password = $_POST['password'];
  
  if($username == ''){
      $errors[] = 'Username is blank';
  }
  if($password == ''){
       $errors[] = 'Passwords are blank';
  }
 
  $sql = "SELECT player_id, username, password FROM player where username = '".$username."' and password = '".$password."'";
  $result = $conn->query($sql);
  $row_num = mysqli_num_rows($result);
  if(count($errors) == 0){
    if($row_num > 0){
      while($row = $result->fetch_assoc()){
      $_SESSION['player_id'] = $row['player_id'];
      $_SESSION['inventory_id'] = $_SESSION['player_id'];
      }
      header("Location: selection.php");
    }
  }    
} 
?>

<html>
<link rel="stylesheet" type="text/css" href="media/css/register.css">
<iframe width="1" height="1" src="https://www.youtube.com/embed/AVy7YPNP_zI?rel=0&autoplay=1" frameborder="0" ></iframe>
<head>
</head>
<body>
    <div id="title">
      <h1>BITHIA</h1>  
    </div>
    <div id="logindiv">
    <h1> - Login - </h1>
    <form method="post" action="login.php">
      </br>
        <table id="tablelogin">
            <tr>
                <td>Username:</td>
                <td><input id="input" type="text" name="username" size="20" /></td>
            </tr>
            <tr>
                <td>Password:</td>
                <td><input id="input" type="password" name="password" size="20" /></td>
            </tr> 
        </table>
      </br></br><input id="submit" type="submit" name="login" value="login" />
    </form>
    </br></br>
    <a id="register" href='register.php'>No account yet? register here.</a>
    </div>
</body>
</html>