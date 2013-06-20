<?php

require("../issdb.php");

	$list1 = "";
	$list2 = "";

	//ticket type
	$sql = "SELECT name FROM otrs_ticket_type WHERE valid_id=1 AND id<>6";
	$type = fetchAll($sql,$db);
	foreach($type as $l1){
		$list1 .= "<tr><td>{$l1['name']}</td></tr>";
	}
	//product/services
	$sql = "SELECT id,name FROM ticket_type WHERE valid_id=1 ORDER BY id ASC";
	$prod = fetchAll($sql,$db);

	//problem type
	$sql = "SELECT name,type_id FROM problem_type WHERE valid_id=1 ORDER BY name ASC";
	$prob = fetchAll($sql,$db);
	
	$pr = Array();
	foreach($prob as $prs){
		$pr[$prs['type_id']][] = $prs['name'];
	}

	foreach($prod as $l2){
		$cnt = 0;
		$cnt = count($pr[$l2['id']]);
		$cnt += 1;
		$list2 .= "<tr><td rowspan=$cnt>{$l2['name']}</td></tr>";
		foreach($pr[$l2['id']] as $lev){
			$list2 .= "<tr><td>{$lev}</td></tr>";
		}
	}

unset($db);
?>
<style>
	table {
		border-collapse:collapse;
	}
	th,td {
		border:1px solid #999;
	}
	th {
		background:#3cf;
	}
</style>
<title>List</title>
<table border=1>
	<tr>
		<th>List of Ticket Type</th>
	</tr>
		<?=$list1;?>
</table>
<br>
<table border=1>
  <tr>
    <th>Product/Services</th>
    <th>Problem Type</th>
  </tr>
    <?=$list2;?>
</table>

