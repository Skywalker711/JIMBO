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

#----------------- HP -------------- #
$sql = "SELECT hp FROM player where player_id = ".$player; 
    $result = $conn->query($sql);
    while($row = $result->fetch_assoc()){
      if($row['hp'] < 0){
        $sql = "UPDATE player SET hp = '0' where player_id = ".$player; 
        $result = $conn->query($sql);
        header("Location: bithia.php");
      } 
    }
    $sql = "SELECT username, hp, money, current_room, faction FROM player where player_id = ".$player; 
    $result = $conn->query($sql);
    while($row = $result->fetch_assoc()){
      $playerhp = $row['hp'];
      $playername = $row['username'];
      $faction = $row['faction'];
      $playermoney = $row['money'];
      $current_room = $row['current_room'];
      if($playermoney < 0){
        $sql = "UPDATE player SET money = 0 where player_id = ".$player;
        $result = $conn->query($sql);
        header("Location: bithia.php");
      }
    }
#------------------------------------#

if($_POST['image']){
  $cur_room = $_GET['room'];
  if($_POST['image'] == 2){
    $item = $_POST['image'];
    $slotvalue = $_POST['slotvalue'];
    $sql = "SELECT name, attack, defence, gold, hp FROM item where item_id = ".$item; 
    $result = $conn->query($sql);
    while($row = $result->fetch_assoc()){
      $itemname = $row['name'];
      $itemattack = $row['attack'];
      $itemdefence = $row['defence'];
      $itemgold = $row['gold'];
      $itemhp = $row['hp'];
    }
    if($playerhp < 100){
      $playerhp = $playerhp + $itemhp;
      if($playerhp > 100){
        $playerhp = 100;
      }
      $sql = "UPDATE player SET hp = ".$playerhp." where player_id = ".$player;
      $result = $conn->query($sql);
      $slot = "slot_".$slotvalue;
      $sql = "UPDATE inventory SET ".$slot." = '0' where inventory_id = ".$player;
      $result = $conn->query($sql);
      header("Location: bithia.php?room=".$cur_room);
    }
  }
  if($_POST['image'] > 29){
    $item = $_POST['image'];
    $slotvalue = $_POST['slotvalue'];
    $slot = "slot_".$slotvalue;
    
    $sql = "SELECT weapon FROM inventory where inventory_id = ".$player;
    $result = $conn->query($sql);
    while($row = $result->fetch_assoc()){
      $oldweapon = $row['weapon'];
      $sql = "SELECT attack, defence FROM item where item_id = ".$oldweapon;
      $result = $conn->query($sql);
      while($row = $result->fetch_assoc()){
        $oldattack = $row['attack'];
        $olddefence = $row['defence'];
      }
    }
    $sql = "SELECT attack, defence FROM item where item_id = ".$item;
    $result = $conn->query($sql);
    while($row = $result->fetch_assoc()){
      $newattack = $row['attack'];
      $newdefence = $row['defence'];
    }
    $sql = "UPDATE inventory SET weapon = ".$item." where inventory_id = ".$player;
    $result = $conn->query($sql);
    $sql = "UPDATE inventory SET ".$slot." = ".$oldweapon." where inventory_id = ".$player;
    $result = $conn->query($sql);
    $sql = "UPDATE player SET attack = attack - ".$oldattack.", defence = defence - ".$olddefence." where player_id = ".$player;
    $result = $conn->query($sql);
    $sql = "UPDATE player SET attack = attack + ".$newattack.", defence = defence + ".$newdefence." where player_id = ".$player;
    $result = $conn->query($sql);
    header("Location: bithia.php?room=".$cur_room);
  }
  if($_POST['image'] > 2 and $_POST['image'] < 23){
   header("Location: bithia.php?room=".$cur_room); 
  }
}
#-------------DIRECTIONS MENU-----------------



if($_POST['Logout']){
     $sql = "UPDATE player SET current_room = '".$_POST['roomvalue']."' WHERE player_id = ".$player;
     $result = $conn->query($sql);    
     header("Location: logout.php");
}
if($_POST['Blacksmith']){
    $sql = "UPDATE player SET current_room = '".$_POST['roomvalue']."' WHERE player_id = ".$player;
    $result = $conn->query($sql);
    header("Location: blacksmith.php");
}
if($_POST['Dwarfshop']){
    $sql = "UPDATE player SET current_room = '".$_POST['roomvalue']."' WHERE player_id = ".$player;
    $result = $conn->query($sql);
    header("Location: shop.php?shop=dwarf");
}
if($_POST['Pirateshop']){
    $sql = "UPDATE player SET current_room = '".$_POST['roomvalue']."' WHERE player_id = ".$player;
    $result = $conn->query($sql);
    header("Location: shop.php?shop=pirate");
}
if($_POST['Outpostshop']){
    $sql = "UPDATE player SET current_room = '".$_POST['roomvalue']."' WHERE player_id = ".$player;
    $result = $conn->query($sql);
    header("Location: shop.php?shop=outpost");
}
if($_POST['Tavern']){
    $sql = "UPDATE player SET current_room = '".$_POST['roomvalue']."' WHERE player_id = ".$player;
    $result = $conn->query($sql);
    header("Location: shop.php?shop=tavern");
}
if($_POST['Merchant']){
    $sql = "UPDATE player SET current_room = '".$_POST['roomvalue']."' WHERE player_id = ".$player;
    $result = $conn->query($sql);
    header("Location: merchant.php");
}
if($_POST['Quest']){
    $sql = "UPDATE player SET current_room = '".$_POST['roomvalue']."' WHERE player_id = ".$player;
    $result = $conn->query($sql);
    header("Location: quest.php");
}
if($_POST['fight']){
    $sql = "UPDATE player SET current_room = '".$_POST['roomvalue']."' WHERE player_id = ".$player;
    $result = $conn->query($sql);
    $_SESSION['enemyid'] = $_POST['enemyid'];
    header("Location: fight.php?launch=1");
}
if($_POST['brokenbridge']){
    $sql = "UPDATE puzzle SET room19 = 1 WHERE player_id = ".$player;
    $result = $conn->query($sql);
    $i = 0;
    while($i < 11){
      $sql = "UPDATE inventory SET slot_".$i." = '0' WHERE slot_".$i." = '15' AND inventory_id = ".$player;
      $result = $conn->query($sql);
      header("Location: bithia.php?room=19");
      $i = $i + 1;
    }
}
if($_POST['goldenkey']){
    $sql = "UPDATE puzzle SET room5 = 1 WHERE player_id = ".$player;
    $result = $conn->query($sql);
    $i = 0;
    while($i < 11){
      $sql = "UPDATE inventory SET slot_".$i." = '0' WHERE slot_".$i." = '19' AND inventory_id = ".$player;
      $result = $conn->query($sql);
      header("Location: bithia.php?room=5");
      $i = $i + 1;
    }
}
if($_POST['rivercross']){
    $sql = "UPDATE puzzle SET room13 = 1 WHERE player_id = ".$player;
    $result = $conn->query($sql);
    $i = 0;
    while($i < 11){
      $sql = "UPDATE inventory SET slot_".$i." = '0' WHERE slot_".$i." = '17' AND inventory_id = ".$player;
      $result = $conn->query($sql);
      header("Location: bithia.php?room=13");
      $i = $i + 1;
    }
}
if($_POST['islandunlock']){
    $sql = "UPDATE puzzle SET room24 = 1 WHERE player_id = ".$player;
    $result = $conn->query($sql);
    $i = 0;
    while($i < 11){
      $sql = "UPDATE inventory SET slot_".$i." = '0' WHERE slot_".$i." = '14' AND inventory_id = ".$player;
      $result = $conn->query($sql);
      header("Location: bithia.php?room=24");
      $i = $i + 1;
    }
}
if($_POST['shoppayment']){
    $sql = "UPDATE puzzle SET room18 = 1 WHERE player_id = ".$player;
    $result = $conn->query($sql);
    $sql = "UPDATE player SET money = money - 2000 where player_id = ".$player;
    $result = $conn->query($sql);
    header("Location: bithia.php?room=18");
}
if($_POST['endboss']){
    $sql = "UPDATE puzzle SET room16 = 1 WHERE player_id = ".$player;
    $result = $conn->query($sql);
    header("Location: bithia.php?room=16");
}
#------------------------- PICK UP ITEM -------------------------------------------------------
if($_POST['roomitem']){
    $item = $_POST['itemid'];
    $room = $_POST['roomvalue'];
    $sql = "UPDATE room_item SET room".$room." = '0' WHERE player_id = ".$player;
    $result = $conn->query($sql);
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
            $sql = "UPDATE inventory SET ".$slot." = ".$item." where inventory_id = ".$player;
            $result = $conn->query($sql);
            header("Location: bithia.php?room=".$room);
          }
        }
      }
      $i = $i + 1;
    }
}
#---------------DATABASE DATA----------------
if($_GET['start'] == 1){
  echo "<div id='hellodiv'>";
  echo "<h1 id='hellomessage'>You are greeted, ".$playername."!</h1></br></br>";
  echo "<h2 id='hellomessage'>Welcome to the magical world of Bithia! You will be playing as Toan, on his quest to gather the 5 scattered Alta and to restore his land of Matataki. May the power of the mighty Dovah be always in your favor!</h2></br>";
  echo "<form action='bithia.php' method = 'POST'><input id='play' type='submit' name='play' value='Enter Bithia!'></form></br></br>";
  echo "<img src = 'media/faction".$faction.".png' id='welcomeimage'>";
  echo "</div>";
}


$sql = "SELECT username, hp, money, attack, defence, current_room, alta FROM player WHERE inventory_id = ".$player;
$result = $conn->query($sql);
while($row = $result->fetch_assoc()){
  
  $hp = $row['hp'];
  $money = $row['money'];
  $attack = $row['attack'];
  $defence = $row['defence'];
  $current_room = $row['current_room'];
  if($_GET['room']){
    $current_room = $_GET['room'];
  }
  $alta = $row['alta'];
}

$sql = "SELECT img_id FROM room WHERE room_id = '".$current_room."'";
$result = $conn->query($sql);
while($row = $result->fetch_assoc()){
  $roombackground = $row['img_id'];
}
#-----------HEALTH POINT SETTINGS------------

$hpgone = 100 - $hp;

#--------------WEAPON SETTINGS---------------

$sql = "SELECT weapon FROM inventory WHERE inventory_id = ".$player;
$result = $conn->query($sql);
while($row = $result->fetch_assoc()){
  if($row['weapon'] > 0){
    $weaponid = $row['weapon'];
    $sql = "SELECT img_url FROM item WHERE item_id = '".$weaponid."'";
    $result = $conn->query($sql);
    while($row = $result->fetch_assoc()){
      $weapon_url = $row['img_url'];
    } 
  } 
}
#-------------- ENEMIES -----------------------
$enemy = 0;
$sql = "SELECT * FROM enemy WHERE enemy_room = ".$current_room;
$result = $conn->query($sql);
while($row = $result->fetch_assoc()){
  if(!empty($row['enemy_id'])){
    $enemyid = $row['enemy_id'];
    $enemyname = $row['name'];
    $enemy = 1;
    if($enemyid > 1 and $enemyid < 7){
      $sql = "SELECT enemy_".$enemyid." FROM enemy_beaten where player_id = ".$player;
      $result = $conn->query($sql);
      while($row = $result->fetch_assoc()){
        if($row['enemy_'.$enemyid] == 1){
          $enemy = 0;          
        }
      }
    }
  }
}
#------------- ROOM ITEMS--------------------
$roomitem = 0;
if($current_room == 9 or $current_room == 14 or $current_room == 27){
  $sql = "SELECT * FROM room_item where player_id = ".$player;
  $result = $conn->query($sql);
  while($row = $result->fetch_assoc()){
    $roomitem = $row['room'.$current_room];
    $sql = "SELECT name FROM item where item_id = ".$roomitem;
    $result = $conn->query($sql);
    while($row = $result->fetch_assoc()){
      $roomitemname = $row['name'];
    }
  }
}

#--------------- LOCKED EXITS ---------------------
if($current_room == 1 or $current_room == 4){
   $i = 1;
   $x = 0;
   while ($i < 11){
    if($x < 1){
      $sql = "SELECT slot_".$i." FROM inventory where inventory_id = '".$player."' AND slot_".$i." = 19";
      $result = $conn->query($sql);
        while($row = $result->fetch_assoc()){
          if($row['slot_'.$i] > 0){
          $x = $x + 1;
        }
      }
    }
  $i = $i + 1;
  }
  if($x > 0){
    $goldenkey = 1;
  }
}
if($current_room == 6 or $current_room == 7 or $current_room == 17){
   $i = 1;
   $x = 0;
   while ($i < 11){
    if($x < 3){
      $sql = "SELECT slot_".$i." FROM inventory where inventory_id = '".$player."' AND slot_".$i." = 15";
      $result = $conn->query($sql);
        while($row = $result->fetch_assoc()){
          if($row['slot_'.$i] > 0){
          $x = $x + 1;
        }
      }
    }
  $i = $i + 1;
  }
  if($x > 1){
    $bridgefix = 1;
  }
}
if($current_room == 12){
   $i = 1;
   $x = 0;
   while ($i < 11){
    if($x < 1){
      $sql = "SELECT slot_".$i." FROM inventory where inventory_id = '".$player."' AND slot_".$i." = 17";
      $result = $conn->query($sql);
       while($row = $result->fetch_assoc()){
          if($row['slot_'.$i] > 0){
          $x = $x + 1;
        }
      }
    }
  $i = $i + 1;
  }
  if($x > 0){
    $rivercross = 1;
  }
}
if($current_room == 21 or $current_room == 23){
   $i = 1;
   $x = 0;
   while ($i < 11){
    if($x < 1){
      $sql = "SELECT slot_".$i." FROM inventory where inventory_id = '".$player."' AND slot_".$i." = 14";
      $result = $conn->query($sql);
       while($row = $result->fetch_assoc()){
          if($row['slot_'.$i] > 0){
          $x = $x + 1;
        }
      }
    }
  $i = $i + 1;
  }
  if($x > 0){
    $islandunlock = 1;
  }
}
if($current_room == 13){
  $sql = "SELECT room18 FROM puzzle where player_id = ".$player;
  $result = $conn->query($sql);
  while($row = $result->fetch_assoc()){
    if($row['room18'] == 0){
      $sql = "SELECT money FROM player where player_id = ".$player;
      $result = $conn->query($sql);
      while($row = $result->fetch_assoc()){
        if($row['money'] > 2000){
          $shoppayment = 1;
        }
      }
    }
  }
}
if($current_room == 15){
  $sql = "SELECT alta FROM player where player_id = ".$player;
  $result = $conn->query($sql);
  while($row = $result->fetch_assoc()){
    if($row['alta'] > 3){
      $endboss = 1;
    }
  }
}

#--------------------------------------------
?>
<html>
  <header>
    <link rel="stylesheet" type="text/css" href="media/css/bithia.css">
    <title></title>
  </header>
  <body>
    <div id = "screen" style="background-image: url('media/background/<?php echo $roombackground; ?>');"></div>
    
    <!--------------------------------------- MINIMAP ---------------------------------------------->
      <?php echo "<a href='map.php?room=".$current_room."'>"; ?>
        <div id="minimapwrap">
          <div id= "minimap" style="background-image: url('media/map/minimap.png');">
          </div> 
          <h1 id="minimaptext">Show minimap</h1>
        </div>
      </a>
    <!---------------------------------------------------------------------------------------------->

    <div id = "hud">
      <!-- Creating HP Bar -->
      <div id = "hpbar">
        <div id = "hp" style="width: <?php echo $hp; ?>%;"></div>
        <div id = "hpgone" style="width: <?php echo $hpgone; ?>%;"></div>
        <h2 id = "number"><?php echo $hp; ?></h2>
      </div>
      <!-- Creating Stats-->
      <div id = "stats">
        <table id = "statstable">
          <th colspan = "2" id="statsheader"><h1 id = "statsheaderh1">- Stats -</h1></th>
          <tr id = "statsrow">
            <td id ="statsleft"><h1>Attack</h1></td>
            <td id ="statsright"><h1><?php echo $attack; ?></h1></td>
          </tr>
          <tr id = "statsrow">
            <td id ="statsleft"><h1>Defence</h1></td>
            <td id ="statsright"><h1><?php echo $defence; ?></h1></td>
          </tr>
          <tr id = "statsrow">
            <td id ="statsleft"><h1>Gold</h1></td>
            <td id ="statsright"><h1><?php echo $money; ?></h1></td>
          </tr>
        </table>
      </div>
      <div id = "currentwep">
        <table id = "currentweptab">
          <tr id = "currentweptr">
            <td id = "currentweptdl"><h1 id="curwep">Current Weapon:</h1></td>
            <td id = "currentweptdr"><?php if($weaponid > 0){ ?><img id="weapon" src="media/item/<?php echo $weapon_url;?>"><?php }else{ ?><h1 id="weaponh1">none</h1><?php } ?></td>
          </tr>
        </table>
      </div>
      
      <!--------------------------------------- IMPLEMENTATION OF THE INVENTORY ---------------------------------------------->
     <h3 id = "inventorytag">- Inventory -</h3>
      <div id = "inventory">
          <table id="inventorytable">
            <tr class="invtr">
            <?php 
            $i = 1;
            while ($i < 6){
              $sql = "SELECT item_id, img_url, slot_".$i." FROM item, inventory where inventory_id = '".$player."' AND item_id = slot_".$i.""; 
              $result = $conn->query($sql);
              while($row = $result->fetch_assoc()){
                if($row['img_url'] == '0'){
                  echo "<td class='invtd'>".$i."</td>";
                }
                else{
                  echo "<td class='invtd'><form action='bithia.php?room=".$current_room."' method='post'><input type='hidden' name = 'slotvalue' value='".$i."'><button id='invbut' type='submit' value = '".$row['item_id']."' name='image'><img id = 'imginv' src='media/item/".$row['img_url']."'/></button></form></td>";
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
              $sql = "SELECT item_id, img_url, slot_".$i." FROM item, inventory where inventory_id = '".$player."' AND item_id = slot_".$i.""; 
              $result = $conn->query($sql);
              while($row = $result->fetch_assoc()){
                if($row['img_url'] == '0'){
                  echo "<td class='invtd'>".$i."</td>";
                }
                else{
                  echo "<td class='invtd'><form action='bithia.php?room=".$current_room."' method='post'><input type='hidden' name = 'slotvalue' value='".$i."'><button id='invbut' type='submit' value = '".$row['item_id']."' name='image'><img id = 'imginv' src='media/item/".$row['img_url']."'/></button></form></td>";

                }
              }
              $i = $i + 1;
            }
            ?>
            </tr>
          </table>
      </div>
      
      <!--------------------------------------- IMPLEMENTATION OF THE INVENTORY ---------------------------------------------->
    
    </div>
    <div id = "prompt">
      <!--------------------------------------- ROOM ---------------------------------------------->
      <table id="roomtable">
        <tr id="roomtr">
      <?php
      $sql = "SELECT description FROM room WHERE room_id = ".$current_room;
      $result = $conn->query($sql);
      while($row = $result->fetch_assoc()){
        echo "<td id='roomtd'><h4>".$row['description']."</h4></td>";
      }
      echo "</tr>";
      $exit = array("n", "no", "o", "zo", "z", "zw", "w", "nw");
      $wind = array("North", "North-East", "East", "South-East", "South", "South-West", "West", "North-West");
      $i = 0;
      $x = 0;
      while($i < 8){
        $sql = "SELECT ".$exit[$i]." FROM room WHERE room_id = ".$current_room;
        $result = $conn->query($sql);
        while($row = $result->fetch_assoc()){
          $roomnumber = $row[$exit[$i]];
          if($roomnumber == 5){
             $sql = "select room5 FROM puzzle where player_id = ".$player;
             $result = $conn->query($sql);
             while($row = $result->fetch_assoc()){
               if($row['room5'] == 0){
                 $roomnumber = 0;
               }
             }
           }
          if($roomnumber == 13){
             $sql = "select room13 FROM puzzle where player_id = ".$player;
             $result = $conn->query($sql);
             while($row = $result->fetch_assoc()){
               if($row['room13'] == 0){
                 $roomnumber = 0;
               }
             }
           }
          if($roomnumber == 16){
             $sql = "select room16 FROM puzzle where player_id = ".$player;
             $result = $conn->query($sql);
             while($row = $result->fetch_assoc()){
               if($row['room16'] == 0){
                 $roomnumber = 0;
               }
             }
           }
          if($roomnumber == 18){
             $sql = "select room18 FROM puzzle where player_id = ".$player;
             $result = $conn->query($sql);
             while($row = $result->fetch_assoc()){
               if($row['room18'] == 0){
                 $roomnumber = 0;
               }
             }
           }
          if($roomnumber == 19){
             $sql = "select room19 FROM puzzle where player_id = ".$player;
             $result = $conn->query($sql);
             while($row = $result->fetch_assoc()){
               if($row['room19'] == 0){
                 $roomnumber = 0;
               }
             }
           }
          if($roomnumber == 24){
             $sql = "select room24 FROM puzzle where player_id = ".$player;
             $result = $conn->query($sql);
             while($row = $result->fetch_assoc()){
               if($row['room24'] == 0){
                 $roomnumber = 0;
               }
             }
           }
           if($roomnumber > 0){
            $sql = "SELECT room_name FROM room WHERE room_id=".$roomnumber;
            $result = $conn->query($sql);
            while($row = $result->fetch_assoc()){
              if($x == 0){
                echo "<tr id='roomtr'><td id='roomtd'><p id='exittext'>From here, you can go to the <b>".$wind[$i]." </b> towards <a id='roomurl' href='bithia.php?room=".$roomnumber."'><b>".$row['room_name']."</b>.</a></p></td></tr>";
              }
              if($x == 1){
                echo "<tr id='roomtr'><td id='roomtd'><p id='exittext'>Another option would be going <b>".$wind[$i]." </b> towards <a id='roomurl' href='bithia.php?room=".$roomnumber."'><b>".$row['room_name']."</b>.</a></p></td></tr>";
              }
              if($x == 2){
                echo "<tr id='roomtr'><td id='roomtd'><p id='exittext'>You can also go <b>".$wind[$i]." </b> towards <a id='roomurl' href='bithia.php?room=".$roomnumber."'><b>".$row['room_name']."</b>.</a></p></td></tr>";
              }
              if($x == 3){
                echo "<tr id='roomtr'><td id='roomtd'><p id='exittext'>In addition, going <b>".$wind[$i]." </b> towards <a id='roomurl' href='bithia.php?room=".$roomnumber."'><b>".$row['room_name']."</b> is also possible.</a></p></td></tr>";
              }
              if($x == 4){
                echo "<tr id='roomtr'><td id='roomtd'><p id='exittext'>Lastly, you can go to the <b>".$wind[$i]." </b> towards <a id='roomurl' href='bithia.php?room=".$roomnumber."'><b>".$row['room_name']."</b>.</a></p></td></tr>";
              }
            $x = $x +1;
            }
          }
        $i = $i + 1;
        }
      } 
      
      
      ?>
      <!--------------------------------------- ROOM ---------------------------------------------->
      <tr id="roomtrsubmit">
        <td id="roomtdsubmit">
          <form id="form" method="post" action="bithia.php">
          <input id="roomsubmit" type="submit" name="Logout" value="Logout">
          <input type="hidden" name="roomvalue" value="<?php echo $current_room; ?>">
          <?php if($current_room == 2){ ?>
          <input id="roomsubmit" type="submit" name="Blacksmith" value="Ask the Smith to craft!">
          <?php ; } ?>
          <?php if($current_room == 3){ ?>
          <input id="roomsubmit" type="submit" name="Quest" value="Take a look at your current Quest!">
          <?php ; } ?>  
          <?php if($current_room == 4){ ?>
          <input id="roomsubmit" type="submit" name="Tavern" value="Get a pint in the Tavern!">
          <?php ; } ?>
          <?php if($current_room == 18){ ?>
          <input id="roomsubmit" type="submit" name="Dwarfshop" value="Buy something in the Dwarfshop!">
          <?php ; } ?>
          <?php if($current_room == 21){ ?>
          <input id="roomsubmit" type="submit" name="Merchant" value="Sell items to the merchant!">
          <?php ; } ?>  
          <?php if($current_room == 26){ ?>
          <input id="roomsubmit" type="submit" name="Pirateshop" value="Buy something in the Pirateshop!">
          <?php ; } ?>
          <?php if($current_room == 28){ ?>
          <input id="roomsubmit" type="submit" name="Outpostshop" value="Buy wood at the Hideout!">
          <?php ; } ?>
          <?php if($enemy == 1){ ?>
          <input type="hidden" name="enemyid" value="<?php echo $enemyid; ?>">
          <input id="roomsubmit" type="submit" name="fight" value="Fight <?php echo $enemyname; ?>!">
          <?php ; } ?>
          <?php if($roomitem > 0){ ?>
          <input type="hidden" name="itemid" value="<?php echo $roomitem; ?>">
          <input id="roomsubmit" type="submit" name="roomitem" value="Pick up the <?php echo $roomitemname; ?>!">
          <?php ; } ?>
          <?php if($bridgefix == 1){ ?>
          <input id="roomsubmit" type="submit" name="brokenbridge" value="Use planks to repair the bridge!">
          <?php ; } ?>
          <?php if($goldenkey == 1){ ?>
          <input id="roomsubmit" type="submit" name="goldenkey" value="Use the key to unlock a secret passage!">
          <?php ; } ?>
          <?php if($rivercross == 1){ ?>
          <input id="roomsubmit" type="submit" name="rivercross" value="Use the raft to cross the river!">
          <?php ; } ?>
          <?php if($shoppayment == 1){ ?>
          <input id="roomsubmit" type="submit" name="shoppayment" value="Pay 2000 gold to enter the Dwarfshop!">
          <?php ; } ?>
          <?php if($islandunlock == 1){ ?>
          <input id="roomsubmit" type="submit" name="islandunlock" value="Use the map to navigate to the Forgotten Islands!">
          <?php ; } ?>
          <?php if($endboss == 1){ ?>
          <input id="roomsubmit" type="submit" name="endboss" value="Go to Sauron's Tower for the final fight!">
          <?php ; } ?>
          </form>
        </td>
      </tr>
    <?php echo "<h3 id='altatag'>Collected Alta's: ".$alta."</h3>"; ?>
    </div>
  </body>
</html>