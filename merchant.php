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
$sellgold = 1;
$player = $_SESSION['player_id'];

if($_POST['Return']){
  header("Location: bithia.php");
}
$sql = "SELECT current_room FROM player WHERE player_id = '".$player."'";
$result = $conn->query($sql);
while($row = $result->fetch_assoc()){
  $current_room = $row['current_room'];
}
$sql = "SELECT img_id FROM room WHERE room_id = '".$current_room."'";
$result = $conn->query($sql);
while($row = $result->fetch_assoc()){
  $roombackground = $row['img_id'];  
}
##################################################################################
if(isset($_POST['sell'])){
  $i = 1;
  $x = 0;
  $item = $_POST['item'];
  while($i < 11){
    if($x < 1){
      $sql = "SELECT slot_".$i." FROM inventory where inventory_id = '".$player."' AND slot_".$i." = '".$item."'"; 
      $result = $conn->query($sql);
      while($row = $result->fetch_assoc()){
        $dbitem = $row['slot_'.$i];
      }
      if($dbitem == $item){
        $sql = "SELECT gold FROM item where item_id = '".$item."'";
        $result = $conn->query($sql);
        while($row = $result->fetch_assoc()){
          $sellgold = 0.5 * $row['gold'];
        }
        if($sellgold > 0){
          $sql = "UPDATE player SET money = money + ".$sellgold." where player_id = '".$player."'";
          $result = $conn->query($sql);
          $sql = "UPDATE inventory SET slot_".$i." = '0' where inventory_id = ".$player;
          $result = $conn->query($sql);
          $x = 1;
        }
      } 
    }  
  $i = $i + 1;
  } 
}      
$sql = "SELECT money FROM player WHERE player_id = '".$player."'";
$result = $conn->query($sql);
while($row = $result->fetch_assoc()){
  $money = $row['money'];
}
##################################################################################
?>
<html>
  <header>
    <link rel="stylesheet" type="text/css" href="media/css/merchant.css">
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
          <td id="tdmoney" colspan="3"><h2>Gold: <?php echo $money; ?></h2></td>
        </tr>
      <?php
        $i = 1;
        $itemcounter = 0;
        while ($i < 11){
          $sql = "SELECT slot_".$i." FROM inventory where inventory_id = '".$player."'"; 
          $result = $conn->query($sql);
          while($row = $result->fetch_assoc()){
            if($row['slot_'.$i] > 0){
              $itemcounter = $itemcounter + 1;    
            }
          }
          $i = $i + 1;
        }      
        
        #TOP ROW OF SHOP ======================================================================================================================================
        $i = 1;
        $datacheck = 0;
        while ($i < 11){
          if($datacheck < 5){
            $sql = "SELECT img_url, item_id, slot_".$i." FROM item, inventory where inventory_id = '".$player."' AND item_id = slot_".$i.""; 
            $result = $conn->query($sql);
            while($row = $result->fetch_assoc()){
              if($row['item_id'] > 0){
                if($datacheck == 0){
                  echo "<tr id='shoptrimg'>";
                }
                echo "<td id='shoptdimg'>";
                echo "<img id='shopimg' src='media/item/".$row['img_url']."'>";
                echo "</td>";
                if($datacheck == 4){
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
        while ($i < 11){
          if($datacheck < 5){
            $sql = "SELECT item_id, gold, slot_".$i.", name FROM item, inventory where inventory_id = '".$player."' AND item_id = slot_".$i.""; 
            $result = $conn->query($sql);
            while($row = $result->fetch_assoc()){
              if($row['item_id'] > 0){
                if($datacheck == 0){
                  echo "<tr id='shoptr'>";
                }
                echo "<td id='shoptd'>";
                $gold = 0.5*$row['gold'];
                echo "<h2>".$row['name']." - ".$gold." Coins</h2>";
                echo "</td>";
                if($datacheck == 4){
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
        while ($i < 11){
          if($datacheck < 5){
            $sql = "SELECT description, item_id, slot_".$i." FROM item, inventory where inventory_id = '".$player."' AND item_id = slot_".$i."";  
            $result = $conn->query($sql);
            while($row = $result->fetch_assoc()){
              if($row['item_id'] > 0){
                if($datacheck == 0){
                  echo "<tr id='shoptr'>";
                }
                echo "<td id='shoptd'>";
                echo $row['description'];
                echo "</td>";
                if($datacheck == 4){
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
        while ($i < 11){
          if($datacheck < 5){
            $sql = "SELECT item_id, slot_".$i." FROM item, inventory where inventory_id = '".$player."' AND item_id = slot_".$i."";  
            $result = $conn->query($sql);
            while($row = $result->fetch_assoc()){
              if($row['item_id'] > 0){
                if($datacheck == 0){
                  echo "<tr id='shoptr'>";
                }
                echo "<td id='shoptd'>";
                echo "<form action = 'merchant.php' method='POST'>";
                echo "<input type='hidden' name='item' value='".$row['item_id']."'>";
                echo "<input id ='purchasebutton' type='submit' name='sell' value='Sell!'>";
                echo "</form>";
                echo "</td>";
                if($datacheck == 4){
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
        if($itemcounter > 5){
          $i = $x;
          $datacheck = 0;
          while ($i < 11){
            if($datacheck < 5){
              $sql = "SELECT img_url, item_id, slot_".$i." FROM item, inventory where inventory_id = '".$player."' AND item_id = slot_".$i.""; 
              $result = $conn->query($sql);
              while($row = $result->fetch_assoc()){
                if($row['item_id'] > 0){
                  if($datacheck == 0){
                    echo "<tr id='shoptrimg'>";
                  }
                  echo "<td id='shoptdimg'>";
                  echo "<img id='shopimg' src='media/item/".$row['img_url']."'>";
                  echo "</td>";
                  if($datacheck == 4){
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
          while ($i < 11){
            if($datacheck < 5){
              $sql = "SELECT name, gold, item_id, slot_".$i." FROM item, inventory where inventory_id = '".$player."' AND item_id = slot_".$i.""; 
              $result = $conn->query($sql);
              while($row = $result->fetch_assoc()){
                if($row['item_id'] > 0){
                  if($datacheck == 0){
                    echo "<tr id='shoptr'>";
                  }
                  echo "<td id='shoptd'>";
                  $gold = 0.5*$row['gold'];
                  echo "<h2>".$row['name']." - ".$gold." Coins</h2>";
                  echo "</td>";
                  if($datacheck == 4){
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
          while ($i < 11){
            if($datacheck < 5){
              $sql = "SELECT description, item_id, slot_".$i." FROM item, inventory where inventory_id = '".$player."' AND item_id = slot_".$i.""; 
              $result = $conn->query($sql);
              while($row = $result->fetch_assoc()){
                if($row['item_id'] > 0){
                  if($datacheck == 0){
                    echo "<tr id='shoptr'>";
                  }
                  echo "<td id='shoptd'>";
                  echo $row['description'];
                  echo "</td>";
                  if($datacheck == 4){
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
          while ($i < 11){
          if($datacheck < 5){
            $sql = "SELECT item_id, slot_".$i." FROM item, inventory where inventory_id = '".$player."' AND item_id = slot_".$i."";  
            $result = $conn->query($sql);
            while($row = $result->fetch_assoc()){
              if($row['item_id'] > 0){
                if($datacheck == 0){
                  echo "<tr id='shoptr'>";
                }
                echo "<td id='shoptd'>";
                echo "<form action = 'merchant.php' method='POST'>";
                echo "<input type='hidden' name='item' value='".$row['item_id']."'>";
                echo "<input id ='purchasebutton' type='submit' name='sell' value='Sell!'>";
                echo "</form>";
                echo "</td>";
                if($datacheck == 4){
                  echo "</tr>";
                  $x = $i + 1;
                }
                $datacheck = $datacheck + 1;
              }
            }
          }
          $i = $i + 1;
        }
        }
        if($itemcounter < 6){
          $i = 1;
          echo "<tr id='shoptr'>";
          while($i < 6){
            echo "<td id='shoptd'>";
            echo "</td>";
            $i = $i + 1; 
          }
          echo "</tr>";
          $i = 1;
          echo "<tr id='shoptr'>";
          while($i < 6){
            echo "<td id='shoptd'>";
            echo "</td>";
            $i = $i + 1; 
          }
          echo "</tr>";
          $i = 1;
          echo "<tr id='shoptr'>";
          while($i < 6){
            echo "<td id='shoptd'>";
            echo "</td>";
            $i = $i + 1; 
          }
          echo "</tr>";
         
        }
 ?>       
      </table>
      <?php 
      if($sellgold == 0){
        echo "<h2 id='warning'><b>This item cannot be sold!</b></h2>";
      }
      
      ?>
      <form action="shop.php" method="post">
        <input id="returnbutton" type="submit" value="Return" name="Return">
      </form>
    </div>
  </body>
</html>