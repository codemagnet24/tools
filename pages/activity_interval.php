<?php

require("../issdb.php");
//require("../pdo.php");

$_POST['date1'] = "2013-01-01";
$_POST['date2'] = "2013-01-01";
if($_POST['date1']){
//$start =  mktime(00,00,01,date("m"),date("d")-40,date("Y"));                                      ;
$start = date("Y-m-d",strtotime($_POST['date1']));
$start_date = "$start 00:00:00";
$end = date("Y-m-d",strtotime($_POST['date2']));
$end_date = "$end 23:59:59";
$start_date1 = date('F d, Y', strtotime($start_date));
$end_date1 = date('F d, Y', strtotime($end_date));
}


$body = "";

//interval
	$sql = "SELECT ticket_id,article_type_id,article_sender_type_id,cast(create_time as time) as create_time "
		."FROM article WHERE "
		."article_type_id IN (9,10,5,1,2) "
		."AND article_sender_type_id IN (1,3) "
		."AND create_time BETWEEN '$start_date' AND '$end_date'";
	$n1 = fetchAll($sql,$db);
	$data = Array();
	foreach($n1 as $nn){
		$type = $nn['article_type_id'];		
		$sender = $nn['article_sender_type_id'];		
		$act = "none";

		if($type==10 && $sender==1){
			$act = "noteE";
		}elseif($type==9 && $sender==1){
			$act = "noteI";
		}elseif($type==5 && $sender==3){
			$act = "phone";
		}elseif($type==1 && $sender==1){
			$act = "amE";
		}elseif($type==1 && $sender==3){
			$act = "cmE";
		}elseif($type==2 && $sender==1){
			$act = "amI";
		}

		$data[$act][$nn['create_time']][] = $nn['ticket_id']." $type $sender";
	}

	echo "<pre>";
//	print_r($data['noteE']);
	echo "</pre>";
  $interval = 0;
  $tnoteI = 0;
  $tnoteE = 0;
  $tphone = 0;
  $tamE = 0;
  $tcmE = 0;
  $tamI = 0;
  $ttotal = 0;
$test = 0;
  while($interval < 24){
    $s1 = "$interval:00:00";
    $s2 = "$interval:59:59";
  $noteI = 0;
  $noteE = 0;
  $phone = 0;
  $amE = 0;
  $cmE = 0;
  $amI = 0;
  $gtotal = 0;
foreach($data as $key => $kl){
        foreach($kl as $lo => $pl){
                if(new DateTIme($lo)>=new DateTime($s1) && new DateTime($lo)<=new DateTime($s2)){
                        $a = count($pl);
                        $$key += $a;
                }
        }
}


	$tnoteI += $noteI;
	$tnoteE += $noteE;
	$tamE += $amE;
	$tcmE += $cmE;
	$tphone += $phone;
	$tamI += $amI;
	$gtotal = $noteI + $noteE + $amE + $cmE + $phone + $amI;
	$ttotal += $gtotal;
	$body .= "<tr class=body><td>$interval:00 - $interval:59</td>"
		."<td>$noteI</td>"
		."<td>$noteE</td>"
		."<td>$amE</td>"
		."<td>$cmE</td>"
		."<td>$phone</td>"
		."<td>$amI</td>"
		."<td>$gtotal</td>"
		."</tr>";

		$interval++;
	
	}
  $total = "<tr class=total><td>Total</td>"
          ."<td>$tnoteI</td>"
          ."<td>$tnoteE</td>"
          ."<td>$tamE</td>"
          ."<td>$tcmE</td>"
          ."<td>$tphone</td>"
          ."<td>$tamI</td>"
          ."<td>$ttotal</td>"
          ."</tr>";

unset($db);

?>

<style>
  .table {
    border-collapse:collapse;
    font-size:12px;
  }
  .table th, .table td {
    border:1px solid #444;
    padding:3px;
  }
  .table th {
    background:#3fa;
  }
  .total {
    background:#999;
  }
  tr.body:nth-child(even) {
    background:#eee;
  }
</style>

<table class="table">
	<tr>
		<th>Time</th>
		<th>Note Internal</th>
		<th>Note External</th>
		<th>Agent - Email</th>
		<th>Customer - Email</th>
		<th>Agent - Phone</th>
		<th>Forward</th>
		<th>Total</th>	
	</tr>
	<?=$body;?>
	<?=$total?>
</table>
