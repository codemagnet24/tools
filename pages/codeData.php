<?php

require("../pdo.php");

        $sql = "SELECT c.rfo_code,t.name,c.layer_1,c.layer_2,c.layer_3,c.layer_4,c.valid_id FROM ecc c, ticket_type t WHERE t.id=c.type_id";
        $ecc = fetchAll($sql,$db);

        $sql = "SELECT id,layer_5 as name,valid_id FROM cc_fault";
        $fault = fetchAll($sql,$db);

	$data = Array();
	foreach($ecc as $ck){
		$data['code'][] = $ck['rfo_code'];
		$data['type'][] = $ck['name'];
		$data['layer_1'][] = $ck['layer_1'];
		$data['layer_2'][] = $ck['layer_2'];
		$data['layer_3'][] = $ck['layer_3'];
		$data['layer_4'][] = $ck['layer_4'];
		$data['valid'][] = $ck['valid_id'];
	}
	foreach($fault as $fk){
		$data['fid'][] = $fk['id'];
		$data['fault'][] = $fk['name'];
		$data['fvalid'][] = $fk['valid_id'];
	}

	echo json_encode($data);

?>
