<?php
$mid=$_GET['id'];
$link = mysqli_connect("localhost","root2","","calendar") or die("Error ".mysqli_error($link));
$query = "select * from form where id='$mid' ";
 //echo $query;
  $result = mysqli_query($link,$query);
  $rr = mysqli_fetch_array($result,MYSQLI_ASSOC);

?>



Title   :   <?php echo $rr['title']; ?></br>
Date       :  <?php echo $rr['date']; ?></br>
Detail   :  <?php echo $rr['detail']; ?></br>
