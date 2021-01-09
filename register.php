<head>
  <link rel="shortcut icon" href="media/css/favicon.ico" type="image/x-icon">
    <link rel="icon" href="media/css/favicon.ico" type="image/x-icon">
</head>
<?php
session_start();
if(isset($_SESSION['player_id'])){
  header("Location: game.php");
}

$errors = array();
include ("connect.php");

if(isset($_POST['login'])){
    $username = preg_replace('/[^A-Za-z]/', '', $_POST['username']);
    $password = $_POST['password'];
    $c_password = $_POST['c_password'];
    
    if($username == ''){
        $errors[] = 'Username is blank';
    }
    if($password == '' || $c_password == ''){
        $errors[] = 'Passwords are blank';
    }
    if($password != $c_password){
        $errors[] = 'Passwords do not match';
    }
  
    $sql = "SELECT username FROM player where username='".$username."'";
    $result = $conn->query($sql);
    $row_num = mysqli_num_rows($result);
    if($row_num > 0) {
      header("Location: register.php");
      die;
    }
  
    if(count($errors) == 0){
    session_start();
    $id_check = 0;
    $player_id = 1;
    while ($id_check == 0){
 		  $sql = "SELECT player_id FROM player where player_id='".$player_id."'";
      $result = $conn->query($sql);
      if($result->num_rows > 0) {
        $player_id = $player_id + 1;
      }
      else {
        $id_check = 1;
      }
    }

    $inventory_id = $player_id;  
    $sql = "INSERT INTO player (player_id, inventory_id, username, password, hp, money, attack, defence, current_room, gender, faction, alta) VALUES ('".$player_id."', '".$inventory_id."', '".$username."', '".$password."', '100', '500', '20', '20', '4', '0', '0', '0')";
    $result = $conn->query($sql);

    #INVENTORY#

    $sql = "INSERT INTO inventory (inventory_id, slot_1, slot_2, slot_3, slot_4, slot_5, slot_6, slot_7, slot_8, slot_9, slot_10, weapon) VALUES ('".$inventory_id."', '30', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0')";
    $result = $conn->query($sql);

    #PUZZLE#
     
    $sql = "INSERT INTO puzzle (player_id, room5, room13, room16, room18, room19, room24) VALUES ('".$player_id."', '0', '0', '0', '0', '0', '0')";
    $result = $conn->query($sql);
    
    #ROOM_ITEM#
     
    $sql = "INSERT INTO room_item (player_id, room9, room14, room27) VALUES ('".$player_id."', '22', '19', '14')";
    $result = $conn->query($sql); 
    
    #ROOM_ITEM#
     
    $sql = "INSERT INTO enemy_beaten (player_id, enemy_2, enemy_3, enemy_4, enemy_5, enemy_6) VALUES ('".$player_id."', '0', '0', '0', '0', '0')";
    $result = $conn->query($sql); 
    
    $sql = "INSERT INTO quest_progress (player_id, quest1, quest2, quest3, quest4, quest5, quest6, quest7, quest8, quest9) VALUES ('".$player_id."', '0', '0', '0', '0', '0', '0', '0', '0', '0')";
    $result = $conn->query($sql); 
      
    $conn->close();
    header("Location: login.php");  
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
    <h1>- Registration -</h1>
    <form method="post" action="">
      </br>
        <table>
            <tr>
                <td>Username</td>
                <td><input id="input" type="text" name="username" maxlength="20"/></td>
            </tr>
            <tr>
                <td>Password</td>
                <td><input id="input" type="password" name="password" maxlength="20"/></td>
            </tr>
            <tr>
              <td>Confirm your password</td>
                <td><input id="input" type="password" name="c_password" maxlength="20"/></td>
            </tr>
            </table>
          </br></br></br><input id="submit" type="submit" name="login" value="Register" /></td>
    </form>
    </br></br>
    <a id="register" href='login.php'>Already an account? login here.</a>
    </div>
</body>
</html>