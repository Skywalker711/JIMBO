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
$combi = 0;

if($_POST['Return']){
  header("Location: bithia.php");
}
if(isset($_POST['1']) and isset($_POST['2'])){
  $item1 = $_POST['1'];
  $item2 = $_POST['2'];
  $combi = 1;
  
  ###############################Combination possebilities#################################
  if($item1 == "15" and $item2 == "15"){
    $combi = 11;
  }
  if($item1 == "6" and $item2 == "6"){
    $combi = 12;
  }
  if($item1 == "20" and $item2 == "16"){
    $combi = 13;
  }
  if($item1 == "16" and $item2 == "20"){
    $combi = 13;
  }
  if($item1 == "22" and $item2 == "22"){
    $combi = 14;
  }

  ######################################Processing##########################################
  
  if($combi == 11){
    $i = 1;
    $x = 0;
    while($i < 11){
        $sql = "SELECT slot_".$i." FROM inventory where slot_".$i." = '15' AND inventory_id = ".$player;
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            if($x < 2){
            $sql = "UPDATE inventory SET slot_".$i." = '0' WHERE inventory_id = ".$player;
            $result = $conn->query($sql);
            $x = $x + 1;
            }
      }
      $sql = "SELECT slot_".$i." FROM inventory WHERE slot_".$i." = '0' AND inventory_id = ".$player;
      $result = $conn->query($sql);
      if($result->num_rows > 0 and $add == 0) {
        $sql = "UPDATE inventory SET slot_".$i." = '20' WHERE inventory_id = ".$player;
        $result = $conn->query($sql);
        $add = 1;
      }
      $i = $i + 1;
    }       
  }
  if($combi == 12){
    $i = 1;
    $x = 0;
    while($i < 11){
      $sql = "SELECT slot_".$i." FROM inventory where slot_".$i." = '6' AND inventory_id = ".$player;
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            if($x < 2){
            $sql = "UPDATE inventory SET slot_".$i." = '0' WHERE inventory_id = ".$player;
            $result = $conn->query($sql);
            $x = $x + 1;
        }
      }
      $result = $conn->query($sql);
      $sql = "SELECT slot_".$i." FROM inventory WHERE slot_".$i." = '0' AND inventory_id = ".$player;
      $result = $conn->query($sql);
      if($result->num_rows > 0 and $add == 0) {
        $sql = "UPDATE inventory SET slot_".$i." = '15' WHERE inventory_id = ".$player;
        $result = $conn->query($sql);
        $add = 1;
      }
      $i = $i + 1;
    }       
  } 
  if($combi == 13){
    $i = 1;
    $x = 0;
    $y = 0;
    while($i < 11){
        $sql = "SELECT slot_".$i." FROM inventory where slot_".$i." = '16' AND inventory_id = ".$player;
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            if($x < 1){
            $sql = "UPDATE inventory SET slot_".$i." = '0' WHERE inventory_id = ".$player;
            $result = $conn->query($sql);
            $x = $x + 1;
            }
        }
        $sql = "SELECT slot_".$i." FROM inventory where slot_".$i." = '20' AND inventory_id = ".$player;
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            if($y < 1){
            $sql = "UPDATE inventory SET slot_".$i." = '0' WHERE inventory_id = ".$player;
            $result = $conn->query($sql);
            $y = $y + 1;
            }
        }
      $sql = "SELECT slot_".$i." FROM inventory WHERE slot_".$i." = '0' AND inventory_id = ".$player;
      $result = $conn->query($sql);
      if($result->num_rows > 0 and $add == 0) {
        $sql = "UPDATE inventory SET slot_".$i." = '17' WHERE inventory_id = ".$player;
        $result = $conn->query($sql);
        $add = 1;
      }
      $i = $i + 1;
    }  
  }
  if($combi == 14){
    $i = 1;
    $x = 0;
    while($i < 11){
      $sql = "UPDATE inventory SET slot_".$i." = '0' WHERE slot_".$i." = '22' AND inventory_id = ".$player;
      $result = $conn->query($sql);
      $sql = "UPDATE inventory SET slot_".$i." = '0' WHERE slot_".$i." = '22' AND inventory_id = ".$player;
      $result = $conn->query($sql);
      $sql = "SELECT slot_".$i." FROM inventory WHERE slot_".$i." = '0' AND inventory_id = ".$player;
      $result = $conn->query($sql);
      if($x == 0){
        $sql = "UPDATE player SET alta = alta + 1 WHERE player_id = ".$player;
        $result = $conn->query($sql);
        $x = 1;
      }
      $i = $i + 1;
    }       
   } 
  }
?>
<html>
  <header>
    <link rel="stylesheet" type="text/css" href="media/css/blacksmith.css">
    <style style="text/css">
      body {
        background-image: url("media/background/background_blacksmith.jpg");
      }
    </style>
    <title>BLACKSMITH</title>
  </header>
  <body>
    <div id="menu">
      <table id="menutable">
        <tr>
          <th id="item1" style="font-size: 35px;">ITEM 1</th>
          <th id="item2" style="font-size: 35px;">ITEM 2</th>
        </tr>
        <form action='blacksmith.php' method='post'>
        <?php
        $i = 1;
        while($i < 11){
              
          $sql = "SELECT name, item_id, img_url, slot_".$i." FROM item, inventory where inventory_id = '".$player."' AND item_id = slot_".$i.""; 
          $result = $conn->query($sql);
          while($row = $result->fetch_assoc()){
            if(isset($row['name']) and $row['item_id'] > 0){
              echo "<tr>";
              echo "<td id='left'>";
              echo "<input type='radio' style='text-align: left' value=".$row['item_id']." name='1'>".$row['name']."</br>";
              echo "</td>";
              echo "<td id='right'>";
              echo $row['name']."<input type='radio' style='text-align: left' value=".$row['item_id']." name='2'></br>";
              echo "</td>";  
              echo "</tr>"; 
            }
          } 
          $i = $i + 1;
        } 
        ?> 

        
          
        <tr id="tdmessage">
          <td colspan='2' id="tdmessage">
          <?php if($combi > 10){ ?>
            <audio autoplay>
              <source src="media/sound/blacksmith.mp3" type="audio/mpeg">
            </audio> 
          <?php ; } ?>
          <?php if($combi == 11){ ?>
            <h1 class="message">Wooden Raft Base made!</h1>
          <?php ; } ?>
          <?php if($combi == 12){ ?>
            <h1 class="message">Plank made!</h1>
          <?php ; } ?>
          <?php if($combi == 13){ ?>
            <h1 class="message">Raft made!</h1>
          <?php ; } ?>
          <?php if($combi == 14){ ?>
            <h1 class="message">Alta made!</h1>
          <?php ; } ?>
          <?php if($combi == 1){ ?>
            <h6 class="message">Can't do that lad!</h6>
          <?php ; } ?>  
         </td>
       </tr>
      </table>
      <input id = "submit" type='submit' name='craft' value ='Combine the selected items!'>
      </form>
      <form id="form" method="post" action="blacksmith.php">
        <input id = "subreturn" type="submit" name="Return" value="Return to Bithia">
      </form>
      </div>
    </br>
    <div id="inventory">
       <table id="inventorytable">
      <tr class="invtr">
          <?php 
          $i = 1;
          while ($i < 6){
            $sql = "SELECT img_url, slot_".$i." FROM item, inventory where inventory_id = '".$player."' AND item_id = slot_".$i.""; 
            $result = $conn->query($sql);
            while($row = $result->fetch_assoc()){
              if($row['img_url'] == '0'){
                echo "<td class='invtd'>".$i."</td>";
              }
              else{
                echo "<td class='invtd'><img src='media/item/".$row['img_url']."'/></td>";
              }
              
            }
            $i = $i + 1;
          }
          ?>
        </tr>
        <tr class="invtr">
          <?php 
          $i = 6;
          while ($i < 11){
            $sql = "SELECT img_url, slot_".$i." FROM item, inventory where inventory_id = '".$player."' AND item_id = slot_".$i.""; 
            $result = $conn->query($sql);
            while($row = $result->fetch_assoc()){
              if($row['img_url'] !== '0'){
                echo "<td class='invtd'><img src='media/item/".$row['img_url']."'/></td>";
              }
              else{
                echo "<td class='invtd'>".$i."</td>";
              }
            }
            $i = $i + 1;
          }
          ?>
        </tr>
      </table>  
    </div>
  </body>
</html>