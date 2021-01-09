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
    echo "<iframe width='1' height='1' src='https://www.youtube.com/embed/wW4sBlfDvOE?rel=0&autoplay=1' frameborder='0' allowfullscreen></iframe>";
  }
}
$sql = "SELECT img_id FROM room WHERE room_id = '".$current_room."'";
$result = $conn->query($sql);
while($row = $result->fetch_assoc()){
  $roombackground = $row['img_id'];  
}
#################################################################################################
if(isset($_POST['buy'])){
  $item = $_POST['item'];
  $sql = "SELECT money, hp FROM player where player_id = ".$player; 
  $result = $conn->query($sql);
  while($row = $result->fetch_assoc()){
    $money = $row['money'];
    $playerhp = $row['hp'];
  }
  if($money > 0){
    $sql = "SELECT name, attack, defence, gold, hp FROM item where item_id = '".$item."'"; 
    $result = $conn->query($sql);
    while($row = $result->fetch_assoc()){
      $itemname = $row['name'];
      $itemattack = $row['attack'];
      $itemdefence = $row['defence'];
      $itemgold = $row['gold'];
      $itemhp = $row['hp'];
    }
    if($money - $itemgold > 0){
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
              if($item == 18){
                if($playerhp < 100){
                  $playerhp = $playerhp + $itemhp;
                  if($playerhp > 100){
                    $playerhp = 100;
                  }
                  $x = 1;
                  $sql = "UPDATE player SET hp = ".$playerhp." where player_id = ".$player;
                  $result = $conn->query($sql);
                  $sql = "UPDATE player SET money = money - ".$itemgold." where player_id = ".$player;
                  $result = $conn->query($sql);
                  header("Location: shop.php");  
                }
              }
              if($item != 18){
                $x = 1;
                $sql = "UPDATE inventory SET ".$slot." = ".$item." where inventory_id = ".$player;
                $result = $conn->query($sql);
                $sql = "UPDATE player SET money = money - ".$itemgold." where player_id = ".$player;
                $result = $conn->query($sql);
                header("Location: shop.php");
              }
          }
        }  
      }
      $i = $i + 1;
      }
    }
  }
}    
$sql = "SELECT money, hp FROM player WHERE player_id = '".$player."'";
$result = $conn->query($sql);
while($row = $result->fetch_assoc()){
  $money = $row['money'];
  $playerhp = $row['hp'];
}
#################################################################################################
?>
<html>
  <header>
    <link rel="stylesheet" type="text/css" href="media/css/shop.css">
    <title>Shop</title>
  </header>
  <body>
  <style style="text/css">
  body {
    background-image: url("media/background/<?php echo $roombackground; ?>");
  }
</style>
    
    <!-- SHOP -->
    
    <div id="shop">
      <table id="shoptable">
      <tr id="trmoney">
          <td id="tdmoney"><h2>Gold: <?php echo $money; ?></h2></td>
          <td id="tdhp"><h2>/ HP: <?php echo $playerhp; ?></h2></td>
        </tr>
      <?php
        $i = 1;
        $itemcounter = 0;
        while ($i < 7){
          $sql = "SELECT item_".$i." FROM shop where shop_id = '".$current_room."'"; 
          $result = $conn->query($sql);
          while($row = $result->fetch_assoc()){
            if($row['item_'.$i] > 0){
              $itemcounter = $itemcounter + 1;    
            }
          }
          $i = $i + 1;
        }      
        
        
        
        
        #TOP ROW OF SHOP ======================================================================================================================================
        $i = 1;
        $datacheck = 0;
        while ($i < 7){
          if($datacheck < 3){
            $sql = "SELECT item_".$i.", img_url, item_id FROM shop, item where shop_id = '".$current_room."' AND item_id = item_".$i.""; 
            $result = $conn->query($sql);
            while($row = $result->fetch_assoc()){
              if($row['item_id'] > 0){
                if($datacheck == 0){
                  echo "<tr id='shoptrimg'>";
                }
                echo "<td id='shoptdimg'>";
                echo "<img id='shopimg' src='media/item/".$row['img_url']."'>";
                echo "</td>";
                if($datacheck == 2){
                  echo "</tr>";
                }
                $datacheck = $datacheck + 1;
              }
            }
          }
          $i = $i + 1;
        }
        $i = 1;
        $datacheck = 0;
        while ($i < 7){
          if($datacheck < 3){
            $sql = "SELECT item_".$i.", name, gold, item_id FROM shop, item where shop_id = '".$current_room."' AND item_id = item_".$i.""; 
            $result = $conn->query($sql);
            while($row = $result->fetch_assoc()){
              if($row['item_id'] > 0){
                if($datacheck == 0){
                  echo "<tr id='shoptrdes'>";
                }
                echo "<td id='shoptddes'>";
                echo "<h2>".$row['name']." - ".$row['gold']." Coins</h2>";
                echo "</td>";
                if($datacheck == 2){
                  echo "</tr>";
                }
                $datacheck = $datacheck + 1;
              }
            }
          }
          $i = $i + 1;
        }
        $i = 1;
        $datacheck = 0;
        while ($i < 7){
          if($datacheck < 3){
            $sql = "SELECT item_".$i.", description, item_id FROM shop, item where shop_id = '".$current_room."' AND item_id = item_".$i.""; 
            $result = $conn->query($sql);
            while($row = $result->fetch_assoc()){
              if($row['item_id'] > 0){
                if($datacheck == 0){
                  echo "<tr id='shoptrdes'>";
                }
                echo "<td id='shoptddes'>";
                echo $row['description'];
                echo "</td>";
                if($datacheck == 2){
                  echo "</tr>";
                  $x = $i + 1;
                }
                $datacheck = $datacheck + 1;
              }
            }
          }
          $i = $i + 1;
        }
        $i = 1;
        $datacheck = 0;
        while ($i < 7){
          if($datacheck < 3){
            $sql = "SELECT item_".$i.", description, item_id FROM shop, item where shop_id = '".$current_room."' AND item_id = item_".$i.""; 
            $result = $conn->query($sql);
            while($row = $result->fetch_assoc()){
              if($row['item_id'] > 0){
                if($datacheck == 0){
                  echo "<tr id='shoptr'>";
                }
                echo "<td id='shoptd'>";
                echo "<form action = 'shop.php' method='POST'>";
                echo "<input type='hidden' name='item' value='".$row['item_id']."'>";
                echo "</br><input id ='purchasebutton' name='buy' type='submit' value='Purchase!'>";
                echo "</form>";
                echo "</td>";
                if($datacheck == 2){
                  echo "</tr>";
                  $x = $i + 1;
                }
                $datacheck = $datacheck + 1;
              }
            }
          }
          $i = $i + 1;
        }
   #BOTTOM LAYER ================================================================================================================================= #
        if($itemcounter > 3){
          $i = $x;
          $datacheck = 0;
          while ($i < 7){
            if($datacheck < 3){
              $sql = "SELECT item_".$i.", img_url, item_id FROM shop, item where shop_id = '".$current_room."' AND item_id = item_".$i.""; 
              $result = $conn->query($sql);
              while($row = $result->fetch_assoc()){
                if($row['item_id'] > 0){
                  if($datacheck == 0){
                    echo "<tr id='shoptrimg'>";
                  }
                  echo "<td id='shoptdimg'>";
                  echo "<img id='shopimg' src='media/item/".$row['img_url']."'>";
                  echo "</td>";
                  if($datacheck == 2){
                    echo "</tr>";
                  }
                  $datacheck = $datacheck + 1;
                }
              }
            }
            $i = $i + 1;
          }
          $i = $x;
          $datacheck = 0;
          while ($i < 7){
            if($datacheck < 3){
              $sql = "SELECT item_".$i.", name, gold, item_id FROM shop, item where shop_id = '".$current_room."' AND item_id = item_".$i.""; 
              $result = $conn->query($sql);
              while($row = $result->fetch_assoc()){
                if($row['item_id'] > 0){
                  if($datacheck == 0){
                    echo "<tr id='shoptrdes'>";
                  }
                  echo "<td id='shoptddes'>";
                  echo "<h2>".$row['name']." - ".$row['gold']." Coins</h2>";
                  echo "</td>";
                  if($datacheck == 2){
                    echo "</tr>";
                  }
                  $datacheck = $datacheck + 1;
                }
              }
            }
            $i = $i + 1;
          }  
          $i = $x;
          $datacheck = 0;
          while ($i < 7){
            if($datacheck < 3){
              $sql = "SELECT item_".$i.", description, item_id FROM shop, item where shop_id = '".$current_room."' AND item_id = item_".$i.""; 
              $result = $conn->query($sql);
              while($row = $result->fetch_assoc()){
                if($row['item_id'] > 0){
                  if($datacheck == 0){
                    echo "<tr id='shoptrdes'>";
                  }
                  echo "<td id='shoptddes'>";
                  echo $row['description'];
                  echo "</td>";
                  if($datacheck == 2){
                    echo "</tr>";
                  }
                  $datacheck = $datacheck + 1;
                }
              }
            }
            $i = $i + 1;
          }  
          $i = $x;
          $datacheck = 0;
          while ($i < 7){
            if($datacheck < 3){
              $sql = "SELECT item_".$i.", description, item_id FROM shop, item where shop_id = '".$current_room."' AND item_id = item_".$i.""; 
              $result = $conn->query($sql);
              while($row = $result->fetch_assoc()){
                if($row['item_id'] > 0){
                  if($datacheck == 0){
                    echo "<tr id='shoptr'>";
                  }
                  echo "<td id='shoptd'>";
                  echo "<form action = 'shop.php' method='POST'>";
                  echo "<input type='hidden' name='item' value='".$row['item_id']."'>";
                  echo "</br><input id ='purchasebutton' name='buy' type='submit' value='Purchase!'>";
                  echo "</form>";
                  echo "</td>";
                  if($datacheck == 2){
                    echo "</tr>";
                  }
                  $datacheck = $datacheck + 1;
                }
              }
            }
            $i = $i + 1;
          }  
        }
        if($itemcounter < 4){
          $i = 1;
          echo "<tr id='shoptr'>";
          while($i < 4){
            echo "<td id='shoptd'>";
            echo "</td>";
            $i = $i + 1; 
          }
          echo "</tr>";
          $i = 1;
          echo "<tr id='shoptr'>";
          while($i < 4){
            echo "<td id='shoptd'>";
            echo "</td>";
            $i = $i + 1; 
          }
          echo "</tr>";
          $i = 1;
          echo "<tr id='shoptr'>";
          while($i < 4){
            echo "<td id='shoptd'>";
            echo "</td>";
            $i = $i + 1; 
          }
          echo "</tr>";
         
        }
 ?>       
      </table>
      <form action="shop.php" method="post">
        <input id="returnbutton" type="submit" value="Return" name="Return">
      </form>
    </div>
    
    <!-- SHOP -->
   
    </br>
    <div id='inventory'>
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
                echo "<td class='invtd'><img id='imginv' src='media/item/".$row['img_url']."'/></td>";
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
                echo "<td class='invtd'><img id='imginv' src='media/item/".$row['img_url']."'/></td>";
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