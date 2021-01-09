<head>
  <link rel="shortcut icon" href="media/css/favicon.ico" type="image/x-icon">
    <link rel="icon" href="media/css/favicon.ico" type="image/x-icon">
</head>
<?php 
$current_room = $_GET['room'];
?>

<html>
  <head>
    <title>Bithia's Map</title>
    <link rel="stylesheet" type="text/css" href="media/css/map.css">
  </head>
  <body>
     <div id = "screen" style="background-image: url('media/map/minimap.png');">
     <?php echo "<a href='bithia.php?room=".$current_room."'>"; ?> 
       <div id = "returndiv"> X </div>
       </div>
     </a>
  </body>
</html>