<html>
 <head>

 <title>Calendar PHP</title>
 <link rel="stylesheet" href="style.css" type="text/css" />
 <script type='text/javascript'>
    function goTo(month, year){
   window.location.href = "day_of_week.php?year="+ year +"&month="+ month;
    }
 </script>
 
 </head>
 <body>
<?php
if(isset($_GET['title'])){
$title = $_GET['title'];
$dateform = $_GET['date'];
$detail = $_GET['detail'];

$link = mysqli_connect("localhost","root2","","calendar") or die("Error ".mysqli_error($link));
  //$query = “INSERT INTO Persons (FirstName, LastName, Age)VALUES ('Peter', 'Griffin',35)”;
  $query = "INSERT INTO form(title,date,detail)VALUES('$title','$dateform','$detail')";
 //echo $query;
  mysqli_query($link,$query);

  

//$mysqli->query("INSERT INTO  calendar (id ,title ,date ,detail)VALUES (NULL ,  'dsfsdfdsf',  '2014-09-09',  'dsfsdf')");
}
?>
<?php
header('Content-Type: text/html; charset=utf-8');


$weekDay = array( 'SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT');
$numday = array (0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31) ;

$thaiMon = array( "01" => "January", "02" => "February", "03" => "March", "04" => "April",
        "05" => "May","06" => "June", "07" => "July", "08" => "August",
        "09" => "September", "10" => "October", "11" => "November", "12" => "December");

//Sun - Sat
$month = isset($_GET['month']) ? $_GET['month'] : date('m'); 	//ถ้าส่งค่าเดือนมาใช้ค่าที่ส่งมา ถ้าไม่ส่งมาด้วย ใช้เดือนปัจจุบัน
$year = isset($_GET['year']) ? $_GET['year'] : date('Y'); 		//ถ้าส่งค่าปีมาใช้ค่าที่ส่งมา ถ้าไม่ส่งมาด้วย ใช้ปีปัจจุบัน


$check = $_GET['date'] ;

if(isset($check)) {
  list( $year , $month, $day) = explode('-', $_GET['date']) ;
}


//วันที่
$startDay = $year.'-'.$month."-01";   		//วันที่เริ่มต้นของเดือน

$timeDate = strtotime($startDay);   		//เปลี่ยนวันที่เป็น timestamp
$lastDay = date("t", $timeDate);   			//จำนวนวันของเดือน

$endDay = $year.'-'.$month."-". $lastDay;  	//วันที่สุดท้ายของเดือน

$startPoint = date('w', $timeDate);   		//จุดเริ่มต้น วันในสัปดาห์

$today = $check ;

echo $today ;
echo $check ;

//ลดเวลาลง 1 เดือน
$prevMonTime = strtotime ( '-1 month' , $timeDate  );
$prevMon = date('m', $prevMonTime);
$prevYear = date('Y', $prevMonTime);

//เพิ่มเวลาขึ้น 1 เดือน
$nextMonTime = strtotime ( '+1 month' , $timeDate  );
$nextMon = date('m', $nextMonTime);
$nextYear = date('Y', $nextMonTime);

$title = "$thaiMon[$month] <strong>".$year."</strong>";

echo '<div id="main">';
echo '<div id="nav">
  <button class="navLeft" onclick="goTo(\''.$prevMon.'\', \''.$prevYear.'\');"><< Previous</button>
  <div class="title">'.$title.'</div>
  <button class="navRight" onclick="goTo(\''.$nextMon.'\', \''.$nextYear.'\');">Next  >></button>
  </div>
  <div style="clear:both"></div>';
foreach ($weekDay as $wday){
	echo "<div class='calendar-block'>".$wday."</div>"; 
	
}
echo "</br>";    				//เปิดแถวใหม่
$col = $startPoint;          	//ให้นับลำดับคอลัมน์จาก ตำแหน่งของ วันในสับดาห์

for($x=1; $x <= $day ; $x++) {

  if($x == $day ) {
     $link = mysqli_connect("localhost","root2","","calendar") or die("Error ".mysqli_error($link));

    $query = "select * from form where 1 ORDER BY id DESC LIMIT 0,1";
 //echo $query;
  $result = mysqli_query($link,$query);
  $rr = mysqli_fetch_array($result,MYSQLI_ASSOC);
  $mid = $rr['id'];
  $link = $_GET['title'] ;
  $link2 = $_GET['date'] ;
  $link3 = $_GET['detail'] ;
  

  
 $numday[$x] = $numday[$x] = $x."</br> <a href='output.php?id=".$mid."'>".$link."</a></br>";
  }
}

if($startPoint < 7){         	//ถ้าวันอาทิตย์จะเป็น 7
  echo str_repeat("<div class='calendar-block'> </div>", $startPoint); //สร้างคอลัมน์เปล่า กรณี วันแรกของเดือนไม่ใช่วันอาทิตย์
}
$row = 0;						//นับว่าครบ 6 แถวหรือยัง
for($i=1; $i <= $lastDay; $i++){ //วนลูป ตั้งแต่วันที่ 1 ถึงวันสุดท้ายของเดือน
  $col++;       					//นับจำนวนคอลัมน์ เพื่อนำไปเช็กว่าครบ 7 คอลัมน์รึยัง

  echo "<div class='calendar-block'>", $numday[$i] , "</div>";  	//สร้างคอลัมน์ แสดงวันที่
  if($col % 7 == false){   		//ถ้าครบ 7 คอลัมน์ให้ขึ้นบรรทัดใหม่
    echo "</br>";   			//ปิดแถวเดิม และขึ้นแถวใหม่
    $col = 0;     				//เริ่มตัวนับคอลัมน์ใหม่
    $row++;
  }
}


$c = 7-$col;
$args = range(1, $c);

/*
if($col < 7){        // ถ้ายังไม่ครบ7 วัน  สร้างคอลัมน์ให้ครบตามจำนวนที่ขาด
  $string = str_repeat("<div class='calendar-block'>%s </div> ", $c);
  echo vsprintf($string, $args);
}*/
echo '</br>';  		//ปิดแถวสุดท้าย

/*
if($row < 7){		//เพิ่มอีกแถว
  $c = max($args);
  $args = range($c+1, $c+7);
  $string = str_repeat("<td class='next_month'>%s</td>", count($args));
  echo vsprintf($string, $args);
}
*/


echo '</main>';




?>






</body>
</html>
