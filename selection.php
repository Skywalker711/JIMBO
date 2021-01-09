<head>
  <link rel="shortcut icon" href="media/css/favicon.ico" type="image/x-icon">
    <link rel="icon" href="media/css/favicon.ico" type="image/x-icon">
</head>
<?php
include ("connect.php");
session_start();

if(empty($_SESSION['player_id'])){
  header("Location: login.php");
}
$player = $_SESSION['player_id'];

$sql = "SELECT gender, faction FROM player WHERE player_id = ".$player;
$result = $conn->query($sql);
while($row = $result->fetch_assoc()){
  $gender = $row['gender'];
  $faction = $row['faction'];
}
if($gender > 0 and $faction > 0){
  header("Location: bithia.php");
}
if($_POST['submit']){
  $gender = $_POST['radiog'];
  $faction = $_POST['radiof'];
  if($gender != 2){
    $sql = "UPDATE player SET gender = '".$gender."', faction = '".$faction."' WHERE player_id = ".$player;
    $result = $conn->query($sql);
    header("Location: bithia.php?start=1");
  }
  if($gender == 2){
    header("Location: https://en.wikipedia.org/wiki/Mental_disorder");
    session_destroy();
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
    <div id="selectordiv">
      <h1>Please select your gender</h1>
      <form id="selectorform" action="" method="post">
        <input type="radio" id="radio1" name="radiog" value="1"/>
        <label id="label1" for="radio1"><img id="img1" src="media/male.png"></label>
        <input type="radio" id="radio2" name="radiog" value="2"/>
        <label id="label2" for="radio2"><img id="img2" src="media/transgender.png"></label>
        <input type="radio" id="radio3" name="radiog" value="3" />
        <label id="label3" for="radio3"><img id="img3" src="media/female.png"></label>
        </br></br></br></br></br></br></br></br></br>
        <h1>Please select your faction</h1> 
        <input type="radio" id="radio4" name="radiof" value="1" />
        <label id="label4" for="radio4"><img src="media/faction1.png"></label>
        <input type="radio" id="radio5" name="radiof" value="2" />
        <label id="label5" for="radio5"><img src="media/faction2.png"></label>
        <input type="radio" id="radio6" name="radiof" value="3" />
        <label id="label6" for="radio6"><img src="media/faction3.png"></label>
        <input id="selectorsubmit" type="submit" value="Enter Bithia" name="submit">
      </form>
    </div>
</body>
</html>