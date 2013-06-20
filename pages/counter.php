<?php
$msc = microtime(true);
?>
    <link href="../assets/css/bootstrap.css" rel="stylesheet">
<style>
	body {
		padding-top:10px;
	}
	.center {
			text-align:center;
	}
	.heading {
		background:#3cf;
	}
	.table-bordered,
	.table-bordered th,
	.table-bordered td {
		border-color:#888;
	}
</style>

<?php

include("pdo.php");



if($_POST['date1']){
//$start =  mktime(00,00,01,date("m"),date("d")-40,date("Y"));                                      ;
$start = date("Y-m-d",strtotime($_POST['date1']));
$start_date = "$start 00:00:00";
$end = date("Y-m-d",strtotime($_POST['date2']));
$end_date = "$end 23:59:59";
$start_date1 = date('F d, Y', strtotime($start_date));
$end_date1 = date('F d, Y', strtotime($end_date));
}


	//initialize
	$head			= "";
	$creates 	= "";
	$open			= "";
	$closed		= "";
	$cTotal		= 0;
	$oTotal		= 0;
	$ccTotal	= 0;
	$cary		= "";
	$opens		= "";
	$cnt			= 1;
	$start = date("Y-m-d",strtotime($start_date));

    //$cl_sql = $db->prepare("SELECT COUNT(*) AS count FROM ticket_history WHERE history_type_id IN (1,27) AND state_id IN (2,3) AND create_time BETWEEN '13-06-05 00:00:00' AND '13-06-05 23:59:59'");
//    $cl_sql = $db->prepare("SELECT COUNT(*) AS count FROM ticket WHERE ticket_state_id IN (11,12,13,1,4,7,6) ");
//    $cl_sql->execute();
//    $cl  = $cl_sql->fetch();
//    $fred = $cl['count'];



//    $ap_sql = $db->prepare("SELECT COUNT(*) AS count FROM ticket WHERE ticket_state_id IN (11,12,13,1,4,7,6) AND create_time < '$start'");
//    $ap_sql->execute();
//    $ap  = $ap_sql->fetch();
//		$debug = $ap['count'];
//		$cary = $debug;
	//day loop
	while($start <= $end_date){
		$add = 0;
		$cnt++;
		$end = date("Y-m-d", strtotime(date("Y-m-d", strtotime($start)) . " +1 day"));
		$prev = date("Y-m-d", strtotime(date("Y-m-d", strtotime($start)) . " -1 day"));
		$head .= "<th>".date("F d",strtotime($start))."</th>";

		$c_sql = $db->prepare("SELECT COUNT(*) AS count FROM ticket WHERE create_time BETWEEN '$start' AND '$end'");
		$c_sql->execute();
		$cs	=	$c_sql->fetch();	
		$creates .= "<td>{$cs['count']}</td>";

		$o_sql = $db->prepare("SELECT COUNT(*) AS count FROM ticket WHERE ticket_state_id IN (11,12,13,1,4,7,6) AND create_time < '$end'");
		$o_sql->execute();
		$os	=	$o_sql->fetch();

		$o2_sql = $db->prepare("SELECT COUNT(*) AS count FROM ticket WHERE ticket_state_id IN (11,12,13,1,4,7,6) AND create_time BETWEEN '$start' AND '$end'");
		$o2_sql->execute();
		$os2	=	$o2_sql->fetch();
		//$add	= $os['count'] + $os2['count'];
		$opens .= "<td>{$os2['count']}</td>";
 
    $cc_sql = $db->prepare("SELECT COUNT(*) AS count FROM ticket_history WHERE history_type_id IN (1,27) AND state_id IN (2,3) AND create_time BETWEEN '$start' AND '$end'");
		//$cc_sql = $db->prepare("SELECT COUNT(*) AS count FROM ticket WHERE ticket_state_id IN (2,3) AND create_time BETWEEN '$start' AND '$end'");
		$cc_sql->execute();
		$ccs	=	$cc_sql->fetch();
		$closed .= "<td>{$ccs['count']}</td>";
//		$cary	+= ($cs['count'] - $ccs['count']);
		$open .= "<td>{$os['count']}</td>";
		$add = $os['count']-$os2['count'];

		$cary 	.= "<td>{$add}</td>";

		$start = $end;

	}


?>
<body>
<div class="container">
	<table class="table table-bordered table-striped"> 
		<thead>
			<tr class="heading">
				<th colspan=<?=$cnt?>><h3 class="center">Ticket counter from <?=$start_date1?> to <?=$end_date1?></h3></th>
			</tr>
			<tr>
				<th>&nbsp;</th>
				<?=$head;?>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>Carry over from prev. day</td>
				<?=$cary?>
			</tr>
			<tr>
				<td>Tickets Created</td>
				<?=$creates;?>
			</tr>

			<tr>
				<td>Remaining Tickets Open within the day</td>
				<?=$opens;?>
			</tr>
			<tr>
				<td>Tickets Closed</td>
				<?=$closed;?>
			</tr>
			<tr>
				<td>Remaining Tickets<br>(Will be carried over the next day)</td>
				<?=$open;?>
			</tr>
		</tbody>
	</table>
<?php
$msc = microtime(true)-$msc;
echo "Page load at ".number_format(($msc*1000),4)." milliseconds";
?>
</div>
</body>
