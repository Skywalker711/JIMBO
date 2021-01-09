<head>
  <link rel="shortcut icon" href="media/css/favicon.ico" type="image/x-icon">
    <link rel="icon" href="media/css/favicon.ico" type="image/x-icon">
</head>
<?php
include("connect.php");
session_start();

if(empty($_SESSION['player_id'])){
  header("Location: login.php");
}

$player = $_SESSION['player_id'];

if($_POST['Return']){
  header("Location: bithia.php");
}
$sql = "SELECT current_room FROM player WHERE player_id = '".$player."'";
$result = $conn->query($sql);
while($row = $result->fetch_assoc()){
  $current_room = $row['current_room'];
  if($current_room == 4){
    echo "<iframe width='1' height='1' src='https://www.youtube.com/embed/K9XRRCPn5XM?rel=0&autoplay=1' frameborder='0' allowfullscreen></iframe>";
  }
}
$sql = "SELECT img_id FROM room WHERE room_id = '".$current_room."'";
$result = $conn->query($sql);
while($row = $result->fetch_assoc()){
  $roombackground = $row['img_id'];  
}
#################################################################################################








#################################################################################################
?>
<html>
  <header>
    <link rel="stylesheet" type="text/css" href="media/css/quest.css">
    <title>Shop</title>
  </header>
  <body>
  <style style="text/css">
  body {
    background-image: url("media/background/<?php echo $roombackground; ?>");
  }
</style>
    

  <div id="shop">
    <h1 id='questheader'>Your Quests:</h1>
    <table id="questtable">
  <?php 
   $i = 1;
   while($i < 10){
    $sql = "SELECT * FROM quest where quest_id = ".$i;
    $result = $conn->query($sql);
    while($row = $result->fetch_assoc()){
      echo "<tr id='questtr'>";
      echo "<td id = 'questtd'><b>Quest ".$row['quest_id']."</b></td>";
      echo "<td id = 'questtddes'>".$row['description']."</td>";
      $sql = "SELECT * FROM quest_progress WHERE player_id = ".$player;
      $result = $conn->query($sql);
      while($row = $result->fetch_assoc()){
        if($row['quest'.$i] == 1){
          echo "<td id = 'questtd'><b>Finished</b></td>";
        }
        if($row['quest'.$i] == 0){
         echo "<td id = 'questtd'><b>Active</b></td>";
        }
      }
      echo "</tr>";
   }
  $i = $i + 1;  
  }
 ?>   
    </table>
    <form id="form" method="post" action="blacksmith.php">
        <input id = "subreturn" type="submit" name="Return" value="Return to Bithia">
      </form>
 </div>
 </body>
</html>