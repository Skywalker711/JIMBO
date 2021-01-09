<head>
  <link rel="shortcut icon" href="media/css/favicon.ico" type="image/x-icon">
    <link rel="icon" href="media/css/favicon.ico" type="image/x-icon">
</head>
<?php
###################################################################################################
######################################## SESSION VALUES ###########################################
###################################################################################################

session_start();
include("connect.php");
$player = $_SESSION['player_id'];
$enemy = $_SESSION['enemyid'];

if(empty($_SESSION['player_id'])){
  header("Location: login.php");
}
if($_POST['Return']){
  header("Location: bithia.php");
}
if($_GET['launch'] == 1){
  $_SESSION['launch'] = 0;
  $_SESSION['end'] = 0;
}
if($_POST['Logout']){
     $sql = "UPDATE player SET current_room = 1 WHERE player_id = ".$player;
     $result = $conn->query($sql);    
     header("Location: logout.php");
}
######################################## INITIATE VALUES ##########################################

  $sql = "SELECT username, hp, attack, defence FROM player where player_id = ".$player;
  $result = $conn->query($sql);
  while($row = $result->fetch_assoc()){ 
    if($_SESSION['launch'] == 0){
      $_SESSION['playerhp'] = $row['hp'];
    }
    $playertophp = $row['hp']; 
    $playerattack = $row['attack'];
    $playerdefence = $row['defence'];
    $playername = $row['username'];
  }
  $sql = "SELECT name, img_id, hp, attack, defence, gold, enemy_room FROM enemy where enemy_id = ".$enemy;
  $result = $conn->query($sql);
  while($row = $result->fetch_assoc()){ 
    if($_SESSION['launch'] == 0){
      $_SESSION['enemyhp'] = $row['hp'];
      $_SESSION['factor'] = $row['hp'];
      $_SESSION['launch'] = 1;
    }
    $enemytophp = $row['hp'];
    $enemyattack = $row['attack'];
    $enemydefence = $row['defence'];
    $enemy_img = "media/enemy/".$row['img_id'];
    $enemygold = $row['gold'];
    $enemyname = $row['name'];
    $enemyroom = $row['enemy_room'];
  }

  $factor = $_SESSION['factor'] / 100;
  $penalty = 0.5 * $enemygold;
 

  $sql = "SELECT img_id FROM room where room_id = ".$enemyroom;
  $result = $conn->query($sql);
  while($row = $result->fetch_assoc()){ 
    $background = $row['img_id'];
  }


###################################################################################################
####################################### PROCESSING VALUES #########################################
###################################################################################################

  if($playerdeath == 0 and $enemydeath == 0){
    if($_POST['attack1'] or $_POST['defence1']){
################################### ENEMY CHOICE / MULTIPLIERS #####################################
      
      $enemychoice = rand(0, 1);
      
      #Critical multipliers
      $playercritical = rand(0, 30);
      $enemycritical = rand(0, 80);
  
      $playermultiplier = 1;
      if($playercritical > 20){
        $playermultiplier = 2;
      }
  
     $enemymultiplier = 1;
     if($enemycritical > 70){
       $enemymultiplier = 2;
     }
   
                        
########################################## PLAYER ATTACKS #########################################
      
        if(isset($_POST['attack1'])){
          if ($enemychoice == 0){
            $enemydamage = ($playerattack * $playermultiplier) - (2 * $enemydefence);
            if($enemydamage > 0){
              $_SESSION['enemyhp'] = $_SESSION['enemyhp'] - $enemydamage; 
            }
          }
          if ($enemychoice == 1){
            $enemydamage = ($playerattack * $playermultiplier) - $enemydefence;                
            $playerdamage = ($enemyattack * $enemymultiplier) - $playerdefence;
            if($playerdamage > 0){
              $_SESSION['playerhp'] = $_SESSION['playerhp'] - $playerdamage;
            }
            if($enemydamage > 0){
              $_SESSION['enemyhp'] = $_SESSION['enemyhp'] - $enemydamage;
            }
          }
        }
      
########################################## PLAYER DEFENDS #########################################
      
        if(isset($_POST['defence1'])){
          if ($enemychoice == 1){
            $playerdamage = ($enemyattack * $enemymultiplier) - (2 * $playerdefence);
            if($playerdamage > 0){
              $_SESSION['playerhp'] = $_SESSION['playerhp'] - $playerdamage;
            }
          }
        }
       }
      }
############################## HP PROCESSING / GAME  END DETERMINATION ############################
$playerhp = $_SESSION['playerhp'] * 0.96;
$enemyhp = $_SESSION['enemyhp'] / $factor * 0.96;
$playerhpgone = 96 - $playerhp;
$enemyhpgone = 96 - $enemyhp;

if($_SESSION['playerhp'] <= 0){
  $_SESSION['playerhp'] = 0;
  $playerhpgone = 96;
  $playerdeath = 1;
}
if($_SESSION['enemyhp'] <= 0){
  $_SESSION['enemyhp'] = 0;
  $enemyhpgone = 96;
  $enemydeath = 1;
}
if($enemydeath == 1 and $playerdeath == 1){
  $enemydeath = 0;
}
if($enemydeath == 1){
  if($enemy == 1){
    $sql = "UPDATE quest_progress SET quest1 = 1 where player_id = ".$player;
    $result = $conn->query($sql);  
  }
  if($enemy == 2){
    $sql = "UPDATE quest_progress SET quest3 = 1 where player_id = ".$player;
    $result = $conn->query($sql);  
  }
  if($enemy == 3){
    $sql = "UPDATE quest_progress SET quest2 = 1 where player_id = ".$player;
    $result = $conn->query($sql);  
  }
  if($enemy == 4){
    $sql = "UPDATE quest_progress SET quest4 = 1 where player_id = ".$player;
    $result = $conn->query($sql);  
  }
  if($enemy == 5){
    $sql = "UPDATE quest_progress SET quest9 = 1 where player_id = ".$player;
    $result = $conn->query($sql);
    echo "<div id='winner'>";
    echo "<h1 id='winmessage'>Congratulations ".$playername."!</h1></br></br>";
    echo "<h2 id='winmessage'>You have succesfully defeated the Baby of Satan and therefore won the game! You can logout and create a new account to play again!</h2></br>";
    echo "<form action='fight.php' method = 'POST'><input id='logout' type='submit' name='Logout' value='Logout'></form>";
    echo "</div>";
  }

  if($_SESSION['end'] != 1){
    $sql = "UPDATE player SET hp = ".$_SESSION['playerhp'].", money = money + ".$enemygold." where player_id = ".$player;
    $result = $conn->query($sql);
    $sql = "SELECT enemy_id, item_drop FROM enemy where enemy_id = ".$enemy;
    $result = $conn->query($sql);
    while($row = $result->fetch_assoc()){
      
      if($row['item_drop'] == 21){
        $sql = "UPDATE player SET alta = alta + 1 where player_id = ".$player;
        $result = $conn->query($sql);
        $sql = "UPDATE enemy_beaten SET enemy_".$enemy." = 1 where player_id = ".$player;
        $result = $conn->query($sql);
        $_SESSION['end'] = 1;
        header("Location: fight.php");
      }
      if($row['item_drop'] == 22){
        $i = 1;
        $x = 0;
        while ($i < 11){
          if($x == 0){
            $sql = "SELECT slot_".$i." FROM inventory where inventory_id = ".$player; 
            $result = $conn->query($sql);
            while($row = $result->fetch_assoc()){
              $slot = 'slot_'.$i;
              if($row[$slot] == 0){
                $x = 1;
                $sql = "UPDATE inventory SET ".$slot." = 22 where inventory_id = ".$player;
                $result = $conn->query($sql);
                
                $sql = "UPDATE enemy_beaten SET enemy_".$enemy." = 1 where player_id = ".$player;
                $result = $conn->query($sql);
                $_SESSION['end'] = 1;
                header("Location: fight.php");
              }
            }
          }
          $i = $i + 1;
        }
      }
    }
  }
}
if($playerdeath == 1){
  if($_SESSION['end'] != 1){
    $sql = "UPDATE player SET hp = 100, money = money - ".$penalty.", current_room = 1 where player_id = ".$player;
    $result = $conn->query($sql);
    $_SESSION['end'] = 1;
    header("Location: fight.php");
  }
}
##################################################################################################
##################################################################################################
?>

<html>
  <head>
    <?php echo $_SESSION['img2']; ?>
    <title>Bithia</title>
    <link rel="stylesheet" type="text/css" href="media/css/fight.css">
  </head>
  <body>
  <style style="text/css">
  body {
    background-image: url("media/background/<?php echo $background; ?>");
  }
  </style>
    <div id="game">
      <table>
        <th>
          <div id="bar">
            <h2 id = "number"><?php echo $_SESSION['playerhp']; ?></h2>
            <div id="healthgood" style="width: <?php echo $playerhp; ?>%; height: 80%;"></div>
            <div id="healthbad" style="width: <?php echo $playerhpgone; ?>%; height: 80%;"></div>
          </div>
        </th>
        <th>
          <div id="bar2">
            <div id="healthgood2" style="width: <?php echo $enemyhp; ?>%; height: 80%;"></div>
            <div id="healthbad2" style="width: <?php echo $enemyhpgone;?>%; height: 80%;"></div>
            <h2 id = "number"><?php echo $_SESSION['enemyhp']; ?></h2>
          </div>
        </th>
        <tr>
          <td>
            <div id="player1">
            <img id="img1" src="media/knight.png">
            </div>
          </td>
          <td>
            <div id="player2">
            <img id="img2" src="<?php echo $enemy_img; ?>">
            </div>
          </td>
          <td><?php       
         ?></td>
        </tr>
        <tr>
          <td>
            <?php if($playerdeath == 0 and $enemydeath == 0) { ?>
            <div id="playerint1">
              <form action="fight.php" method="POST">
                <button type="submit" value="20" name="attack1">attack</button>
                <button type="submit" value="10" name="defence1">defend</button>
              </form>  
            </div>
            <?php ; } ?>
          </td>
        </tr>
      </table>
      <?php if($playerdeath == 1){ 
              echo '<h1 style="margin: auto auto; color: #FF0000; font-size: 50px;">DEFEAT</h1>';
              echo '<form id="form" method="post" action= "fight.php"><input type="submit" name="Return" value="Respawn"></form>';
            } 
            if($enemydeath == 1){
              echo "<h1 style='margin: auto auto; color: #FF0000; font-size: 50px;'>VICTORY</h1>";
              echo "<audio autoplay>";
              echo "<source src='media/sound/soundvictory.mp3' type='audio/mpeg'>";
              echo "</audio>";
              echo "<audio autoplay>";
              echo "<source src='media/sound/soundvictorytrumpet.mp3' type='audio/mpeg'>";
              echo "</audio>";
              echo '<form id="form" method="post" action= "fight.php"><input type="submit" name="Return" value="Leave the fight"></form>';
            } 
      ?>
    </div>
  </body>
</html>